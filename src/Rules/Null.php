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
class Null extends Rule
{
    public $message = '{value} is not null.';

    public function validate($data, ValidationContext $context)
    {
        return $data === null;
    }
}
