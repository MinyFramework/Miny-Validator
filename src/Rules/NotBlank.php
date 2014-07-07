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
class NotBlank extends Blank
{
    public $message = 'This property should not be blank.';

    public function validate($data, ValidationContext $context)
    {
        return !parent::validate($data, $context);
    }
}
