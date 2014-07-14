<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <bugadani@gmail.com>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator;

class ValidationContext
{
    public $errors;
    public $validator;
    public $scenarios;
    private $currentProperty;
    private $currentObject;
    private $propertyStack = array();
    private $objectStack = array();

    public function __construct(ValidatorService $validator, array $scenarios = null)
    {
        $this->validator = $validator;
        $this->scenarios = $scenarios ? : array('default');
    }

    public function recordError($property, $value, Rule $rule)
    {
        if (!isset($this->errors)) {
            $this->errors = new ErrorList;
        }
        $this->errors->add($property, $rule->getMessage($value, $this));
    }

    public function enterObject($object)
    {
        $this->objectStack[] = $this->currentObject;
        $this->currentObject = $object;
    }

    public function leaveObject()
    {
        $this->currentObject = array_pop($this->objectStack);
    }

    public function getCurrentObject()
    {
        return $this->currentObject;
    }

    public function enterProperty($property)
    {
        $this->propertyStack[] = $this->currentProperty;
        $this->currentProperty = $property;
    }

    public function leaveProperty()
    {
        $this->currentProperty = array_pop($this->propertyStack);
    }

    public function getCurrentProperty()
    {
        return $this->currentProperty;
    }
}
