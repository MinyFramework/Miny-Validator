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
class True extends Rule
{
    public $message = 'This property should be true.';

    public function validate($data, ValidationContext $context)
    {
        return $data === true || $data === 1 || $data === '1';
    }
}
