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
 * @Attribute('pattern', required: true, type: 'string')
 */
class Pattern extends Rule
{
    public $pattern;
    public $message = '"{value}" does not match the {pattern} pattern.';

    public function validate($data, ValidationContext $context)
    {
        if (!isset($this->pattern)) {
            throw new RuleDefinitionException('Pattern::$pattern must be set.');
        }
        if (!is_string($data)) {
            if (!is_object($data) || !method_exists($data, '__toString')) {
                throw new \InvalidArgumentException('The Pattern Rule expects a string value.');
            }
        }

        return preg_match($this->pattern, $data);
    }

    public function getMessage($value, ValidationContext $context)
    {
        $message = parent::getMessage($value, $context);

        return str_replace('{pattern}', $this->pattern, $message);
    }
}
