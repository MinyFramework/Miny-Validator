<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <bugadani@gmail.com>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator;

use Modules\Annotation\Comment;
use Modules\Annotation\Reader;

class ValidatorService
{
    /**
     * @var Reader
     */
    private $annotation;

    /**
     * @var ValidationContext
     */
    private $context;

    /**
     * @var RuleSet[]
     */
    private $metadata = array();

    public function setAnnotationReader(Reader $reader)
    {
        $this->annotation = $reader;
    }

    /**
     * @param string  $class
     * @param RuleSet $ruleSet
     */
    public function register($class, RuleSet $ruleSet)
    {
        $this->metadata[$class] = $ruleSet;
    }

    /**
     * @param                   $object
     * @param string|array      $scenarios
     * @param RuleSet           $ruleSet
     * @param ValidationContext $context
     *
     * @return bool
     */
    public function validate(
        $object,
        $scenarios = null,
        RuleSet $ruleSet = null,
        ValidationContext $context = null
    ) {
        $ruleSet = $ruleSet ? : $this->getMetadata($object);
        $scenarios     = $this->getScenarios($scenarios, $object, $ruleSet);
        $this->context = $context ? : new ValidationContext($this, $scenarios);
        foreach ($scenarios as $scenario) {
            $rules = $ruleSet->getRulesForScenario($scenario);
            if (!$this->validateScenario($object, $rules)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param                   $value
     * @param Rule|Rule[]       $rules
     * @param string|null       $name
     * @param ValidationContext $context
     *
     * @throws \InvalidArgumentException
     *
     * @return bool
     */
    public function validateValue($value, $rules, $name = null, ValidationContext $context = null)
    {
        $this->context = $context ? : new ValidationContext($this);

        if (!is_array($rules)) {
            $rules = array($rules);
        }
        $valid = true;
        $this->context->enterProperty($name);
        foreach ($this->context->scenarios as $scenario) {
            foreach ($rules as $rule) {
                if (!$rule instanceof Rule) {
                    throw new \InvalidArgumentException('Invalid rule.');
                }
                if (!in_array($scenario, (array)$rule->for)) {
                    continue;
                }
                if (!$rule->validate($value, $this->context)) {
                    //record error
                    $this->context->recordError($name, $value, $rule);
                    $valid = false;
                }
            }
        }
        $this->context->leaveProperty();

        return $valid;
    }

    private function validateScenario($object, array $scenario)
    {
        $valid = true;
        foreach ($scenario['property'] as $property => $validators) {
            $valid &= $this->validateProperty($object, $property, $validators);
        }
        foreach ($scenario['getter'] as $getter => $validators) {
            $valid &= $this->validateMethod($object, $validators, $getter);
        }

        return $valid;
    }

    /**
     * @param        $object
     * @param        $property
     * @param Rule[] $validators
     *
     * @return bool
     */
    private function validateProperty($object, $property, $validators)
    {
        $valid = true;
        $this->context->enterProperty($property);
        foreach ($validators as $validator) {
            $value = $object->$property;
            if (!$validator->validate($value, $this->context)) {
                //record error
                $this->context->recordError($property, $value, $validator);
                $valid = false;
            }
        }
        $this->context->leaveProperty();

        return $valid;
    }

    /**
     * @param        $object
     * @param Rule[] $validators
     * @param        $getter
     *
     * @return bool
     */
    private function validateMethod($object, $validators, $getter)
    {
        $valid  = true;
        $method = $this->findGetter($object, $getter);
        $this->context->enterProperty($getter);
        foreach ($validators as $validator) {
            $value = $object->$method();
            if (!$validator->validate($value, $this->context)) {
                //record error
                $this->context->recordError($getter, $value, $validator);
                $valid = false;
            }
        }
        $this->context->leaveProperty();

        return $valid;
    }

    private function findGetter($object, $getter)
    {
        if (method_exists($object, $getter)) {
            return $getter;
        }
        $getter = ucfirst($getter);
        foreach (array('get', 'has', 'is') as $prefix) {
            $methodName = $prefix . $getter;
            if (method_exists($object, $methodName)) {
                return $methodName;
            }
        }
        throw new \UnexpectedValueException("Getter not found for {$getter}");
    }

    private function getRuleSetFromAnnotation(RuleSet $ruleSet, $object)
    {
        $classAnnotations    = $this->annotation->readClass($object);
        $propertyAnnotations = $this->annotation->readProperties($object);
        $methodAnnotations   = $this->annotation->readMethods($object);

        $scenarioListClassName = 'Modules\\Validator\\ScenarioList';
        if ($classAnnotations->hasAnnotationType($scenarioListClassName)) {
            $scenarios = array();
            foreach ($classAnnotations->getAnnotationType($scenarioListClassName) as $annotation) {
                $scenarios = array_merge($scenarios, $annotation->scenarios);
            }
            $ruleSet->setScenarioList($scenarios);
        }

        $this->addRulesFromAnnotation($propertyAnnotations, $ruleSet, 'property');
        $this->addRulesFromAnnotation($methodAnnotations, $ruleSet, 'getter');
    }

    /**
     * @param Comment[] $annotations
     * @param RuleSet   $ruleSet
     */
    private function addRulesFromAnnotation($annotations, RuleSet $ruleSet)
    {
        foreach ($annotations as $property => $comment) {
            foreach ($comment->getAnnotations() as $annotationSet) {
                foreach ($annotationSet as $annotation) {
                    if ($annotation instanceof Rule) {
                        $ruleSet->property($property, $annotation);
                    }
                }
            }
        }
    }

    /**
     * @param         $scenarios
     * @param         $object
     * @param RuleSet $ruleSet
     *
     * @return string[]
     */
    private function getScenarios($scenarios, $object, RuleSet $ruleSet)
    {
        if ($scenarios === null) {
            if ($object instanceof ScenarioListProvider) {
                $scenarios = $object->getScenarioList();
            } else {
                $scenarios = $ruleSet->getScenarioList();
            }
        }

        return (array)$scenarios;
    }

    /**
     * @return ErrorList|null
     */
    public function getErrors()
    {
        return $this->context->errors;
    }

    /**
     * @param object $object
     *
     * @return RuleSet
     */
    public function getMetadata($object)
    {
        $class = get_class($object);
        if (!isset($this->metadata[$class])) {
            if ($object instanceof Validable) {
                $ruleSet = $object->getValidationInfo();
            } else {
                $ruleSet = new RuleSet();
            }
            if (isset($this->annotation)) {
                $this->getRuleSetFromAnnotation($ruleSet, $object);
            }
            $this->register($class, $ruleSet);
        }

        return $this->metadata[$class];
    }
}
