<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator;

use UnexpectedValueException;

class Validator
{
    protected function loadConstraints(iValidable $object)
    {
        $class = new Descriptor;
        $object->getValidationInfo($class);
        return $class;
    }

    public function validate(iValidable $object, $scenario = NULL)
    {
        $class = $this->loadConstraints($object);
        $valid = true;
        /** @var $violations ConstraintViolationList[] */
        $violations = array();
        foreach ($class->getConstraints(Descriptor::CLASS_CONSTRAINT) as $array) {
            /** @var $constraint Constraint */
            foreach ($array as $constraint) {
                $is_valid = $this->validateValue($object, $constraint, $scenario);
                if ($is_valid !== true) {
                    $list = $constraint->getViolationList();
                    if (!isset($violations[Descriptor::PROPERTY_CONSTRAINT])) {
                        $violations[Descriptor::PROPERTY_CONSTRAINT] = $list;
                    } else {
                        $violations[Descriptor::PROPERTY_CONSTRAINT]->addViolationList($list);
                    }
                    $valid = false;
                }
            }
        }

        foreach ($class->getConstraints(Descriptor::GETTER_CONSTRAINT) as $getter => $array) {
            foreach ($array as $constraint) {
                $data = call_user_func(array($object, $getter));
                $is_valid = $this->validateValue($data, $constraint, $scenario);
                if ($is_valid !== true) {
                    $list = $constraint->getViolationList();
                    if (!isset($violations[$getter])) {
                        $violations[$getter] = $list;
                    } else {
                        $violations[$getter]->addViolationList($list);
                    }
                    $valid = false;
                }
            }
        }

        foreach ($class->getConstraints(Descriptor::PROPERTY_CONSTRAINT) as $property => $array) {
            foreach ($array as $constraint) {
                $data = $object->$property;
                $is_valid = $this->validateValue($data, $constraint, $scenario);
                if ($is_valid !== true) {
                    $list = $constraint->getViolationList();
                    if (!isset($violations[$property])) {
                        $violations[$property] = $list;
                    } else {
                        $violations[$property]->addViolationList($list);
                    }
                    $valid = false;
                }
            }
        }
        if ($valid) {
            return true;
        }

        return $violations;
    }

    public function validateValue($data, Constraint $constraint, $scenario = NULL)
    {
        if (!$constraint->constraintApplies($scenario)) {
            return true;
        }
        if (!method_exists($constraint, 'validate')) {
            if (!$data instanceof iValidable) {
                $message = 'Cannot validate variable.';
                throw new UnexpectedValueException($message);
            }
            if (!$this->validate($data, $scenario)) {
                $constraint->addViolation($constraint->message);
                return false;
            }
        } elseif (!$constraint->validate($data)) {
            return $constraint->getViolationList();
        }
        return true;
    }

}
