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
class Blank extends Rule
{
    public $message = 'This property should be blank.';

    public function validate($data, ValidationContext $context)
    {
        return $data === '' || $data === null;
    }
}
