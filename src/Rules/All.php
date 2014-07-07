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
class All extends Rule
{
    /**
     * @var Rule[]
     */
    public $rules;

    public function validate($data, ValidationContext $context)
    {
        $valid = true;

        if (!is_array($data) && !$data instanceof \Traversable) {
            throw new \InvalidArgumentException('The All Rule expects an array or countable object.');
        }
        foreach ($data as $key => $value) {
            $name = $context->getCurrentProperty() . "[{$key}]";
            $valid &= $context->validator->validateValue($value, $this->rules, $name, $context);
        }

        return $valid;
    }
}
