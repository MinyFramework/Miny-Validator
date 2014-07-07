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
 * @DefaultAttribute data
 * @Attribute('data', required: true)
 */
abstract class Comparison extends Rule
{
    public $data;

    public function getMessage($value, ValidationContext $context)
    {
        $message = parent::getMessage($value, $context);

        return str_replace('{compared}', $this->data, $message);
    }
}
