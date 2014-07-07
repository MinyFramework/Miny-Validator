<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <bugadani@gmail.com>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator\Rules;

use Modules\Validator\ValidationContext;

/**
 * @Annotation
 */
class LessThan extends Comparison
{
    public $message = '{value} should be less than {compared}.';

    public function validate($data, ValidationContext $context)
    {
        return $data < $this->data;
    }
}
