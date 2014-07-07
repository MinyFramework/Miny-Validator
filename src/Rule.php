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
 * @Attribute('for', setter: 'setScenario')
 * @Attribute('message', type: 'string')
 */
abstract class Rule
{
    public $for = 'default';
    public $message = 'This value is invalid';

    public static function fromArray(array $options)
    {
        $rule = new static();
        foreach ($options as $name => $value) {
            $rule->$name = $value;
        }

        return $rule;
    }

    abstract public function validate($data, ValidationContext $context);

    final public function setScenario($scenario)
    {
        if (is_string($scenario)) {
            $this->for = $scenario;
        } elseif (is_array($scenario)) {
            foreach ($scenario as $for) {
                if (!is_string($for)) {
                    throw new \InvalidArgumentException('Scenario must be a string or an array of strings');
                }
            }
            $this->for = $scenario;
        } else {
            throw new \InvalidArgumentException('Scenario must be a string or an array of strings');
        }
    }

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
