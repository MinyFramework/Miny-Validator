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
 * @Attribute('array', type: 'bool')
 */
class Valid extends Rule
{
    public $array = false;

    public function validate($data, ValidationContext $context)
    {
        if ($this->array) {
            if (!is_array($data) || !$data instanceof \Traversable) {
                throw new \InvalidArgumentException('Expected an array');
            }
            $valid = true;
            foreach ($data as $item) {
                $valid &= $context->validator->validate($item, $context->scenarios, null, $context);
            }

            return $valid;
        } else {
            return $context->validator->validate($data, $context->scenarios, null, $context);
        }
    }
}
