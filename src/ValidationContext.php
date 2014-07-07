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
    private $propertyStack = array();
    private $currentProperty;

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
