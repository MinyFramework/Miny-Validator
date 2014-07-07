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
 */
class False extends Rule
{
    public $message = '{value} should be false.';

    public function validate($data, ValidationContext $context)
    {
        return $data === false || $data === 0 || $data === '0';
    }
}
