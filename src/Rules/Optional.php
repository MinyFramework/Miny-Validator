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
 * @DefaultAttribute rules
 * @Attribute('rules', type: {Rule}, required: true)
 */
class Optional extends Rule
{
    /**
     * @var Rule[]
     */
    public $rules;

    public function validate($data, ValidationContext $context)
    {
        if ($data === null || $data === '') {
            return true;
        }

        return $context->validator->validateValue(
            $data,
            $this->rules,
            $context->getCurrentProperty(),
            $context
        );
    }
}
