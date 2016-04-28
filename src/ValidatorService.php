<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <bugadani@gmail.com>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator;

use Miny\Event\EventDispatcher;
use Annotiny\Comment;
use Annotiny\Reader;
use Modules\Validator\Events\InvalidEvent;
use Modules\Validator\Events\PostValidationEvent;
use Modules\Validator\Events\PreValidationEvent;
use Modules\Validator\Events\ValidEvent;

class ValidatorService
{
    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

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
    private $metadata = [];

    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

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
        $ruleSet       = $ruleSet ? : $this->getMetadata($object);
        $scenarios     = $this->getScenarios($scenarios, $object, $ruleSet);
        $this->context = $context ? : new ValidationContext($this, $scenarios);
        $this->context->enterObject($object);
        $this->eventDispatcher->raiseEvent(
            new PreValidationEvent($object, $this->context)
        );
        foreach ($scenarios as $scenario) {
            $rules = $ruleSet->getRulesForScenario($scenario);
            if (!$this->validateScenario($object, $rules)) {
                $this->eventDispatcher->raiseEvent(
                    new InvalidEvent($object, $this->context)
                );
                $this->eventDispatcher->raiseEvent(
                    new PostValidationEvent($object, $this->context)
                );
                $this->context->leaveObject();

                return false;
            }
        }
        $this->eventDispatcher->raiseEvent(
            new ValidEvent($object, $this->context)
        );
        $this->eventDispatcher->raiseEvent(
            new PostValidationEvent($object, $this->context)
        );
        $this->context->leaveObject();

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

        $valid = true;
        $this->context->enterProperty($name);

        $this->eventDispatcher->raiseEvent(
            new PreValidationEvent($value, $this->context)
        );
        if (!is_array($rules)) {
            $rules = [$rules];
        }
        foreach ($rules as $rule) {
            if (!$rule instanceof Rule) {
                throw new \InvalidArgumentException('Invalid rule.');
            }
        }
        foreach ($this->context->scenarios as $scenario) {
            foreach ($rules as $rule) {
                if (!in_array($scenario, $rule->for)) {
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

        if ($valid) {
            $this->eventDispatcher->raiseEvent(
                new ValidEvent($value, $this->context)
            );
        } else {
            $this->eventDispatcher->raiseEvent(
                new InvalidEvent($value, $this->context)
            );
        }

        $this->eventDispatcher->raiseEvent(
            new PostValidationEvent($value, $this->context)
        );

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
        if (is_callable($object, $getter)) {
            return $getter;
        }
        $getter = ucfirst($getter);
        foreach (['get', 'has', 'is'] as $prefix) {
            $methodName = $prefix . $getter;
            if (is_callable($object, $methodName)) {
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
            $scenarios = [];
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
            if ($object instanceof ScenarioListProviderInterface) {
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
