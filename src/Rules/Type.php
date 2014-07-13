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
 * @DefaultAttribute type
 * @Attribute('type', required: true, type: 'string')
 */
class Type extends Rule
{
    public $type;
    public $message = 'This property has an invalid type.';

    public function validate($data, ValidationContext $context)
    {
        $isTypeFunction = "is_{$this->type}";
        $ctypeFunction  = "ctype_{$this->type}";

        if (function_exists($isTypeFunction)) {
            return $isTypeFunction($data);
        }

        if (function_exists($ctypeFunction)) {
            return $ctypeFunction($data);
        }

        return $data instanceof $this->type;
    }
}
