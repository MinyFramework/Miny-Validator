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
class NotNull extends Null
{
    public $message = 'This property should not be null.';

    public function validate($data, ValidationContext $context)
    {
        return !parent::validate($data, $context);
    }
}
