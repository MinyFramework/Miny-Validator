<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <bugadani@gmail.com>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator\Rules;

use Modules\Validator\Rule;
use Modules\Validator\ValidationContext;

/**
 * @Annotation
 * @Attribute('min', type: 'int', required: true)
 * @Attribute('max', type: 'int', required: true)
 */
class Range extends Rule
{
    public $min;
    public $max;
    public $message = 'This property should be between {min} and {max}.';

    public function validate($data, ValidationContext $context)
    {
        if ($data < $this->min || $data > $this->max) {
            return false;
        }

        return true;
    }

    public function getMessage($value, ValidationContext $context)
    {
        return str_replace(array('{min}', '{max}'), array($this->min, $this->max), $this->message);
    }
}
