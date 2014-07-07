<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <bugadani@gmail.com>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator\Rules;

use Modules\Validator\Exceptions\RuleDefinitionException;
use Modules\Validator\Rule;
use Modules\Validator\ValidationContext;

/**
 * @Annotation
 */
class Choice extends Rule
{
    public $choices;
    public $source;
    public $multiple = false;
    public $message = '{value} is not one of the following: {choices}';

    public function validate($data, ValidationContext $context)
    {
        if (!isset($this->choices)) {
            if (!isset($this->source)) {
                throw new RuleDefinitionException('The Choice Rule requires either an array or a callback for the set of choices.');
            }
            $this->choices = call_user_func($this->source);
        }
        if (!is_array($this->choices)) {
            throw new RuleDefinitionException('The Choice Rule requires either an array or a callback for the set of choices.');
        }
        $valid = true;
        if ($this->multiple && (is_array($data) || $data instanceof \Traversable)) {
            foreach ($data as $item) {
                $valid &= in_array($item, $this->choices);
            }
        } else {
            $valid &= in_array($data, $this->choices);
        }

        return $valid;
    }

    public function getMessage($value, ValidationContext $context)
    {
        $message = parent::getMessage($value, $context);

        return str_replace('{values}', implode(', ', $this->choices), $message);
    }
}
