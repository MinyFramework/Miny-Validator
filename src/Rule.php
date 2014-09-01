<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <bugadani@gmail.com>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator;

/**
 * @Annotation
 * @DefaultAttribute message
 * @Attribute('for')
 * @Attribute('message', type: 'string')
 * @Target('method', 'property')
 */
abstract class Rule
{
    private $for = array('default');
    public $message = 'This value is invalid';

    public static function fromArray(array $options)
    {
        $rule = new static();
        foreach ($options as $name => $value) {
            $rule->__set($name, $value);
        }

        return $rule;
    }

    public function __set($key, $value)
    {
        switch ($key) {
            case 'for':
                if (!is_array($value)) {
                    $value = array($value);
                }
                foreach ($value as $for) {
                    if (!is_string($for)) {
                        throw new \InvalidArgumentException('Scenario must be a string or an array of strings');
                    }
                }
                break;
        }
        $this->$key = $value;
    }

    public function __get($key)
    {
        switch ($key) {
            case 'for':
                return $this->for;
        }
        return null;
    }

    abstract public function validate($data, ValidationContext $context);

    public function getMessage($value, ValidationContext $context)
    {
        if (!is_scalar($value)) {
            if (is_array($value)) {
                $count = count($value);
                $value = "Array ({$count})";
            } else {
                $value = get_class($value);
            }
        }

        $message = str_replace('{property}', $context->getCurrentProperty(), $this->message);

        return str_replace('{value}', $value, $message);
    }
}
