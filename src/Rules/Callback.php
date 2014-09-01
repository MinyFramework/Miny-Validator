<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <bugadani@gmail.com>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator\Rules;

use Modules\Validator\Exceptions\RuleDefinitionException;
use Modules\Validator\Rule;
use Modules\Validator\ValidationContext;

/**
 * @Annotation
 * @DefaultAttribute function
 * @Attribute('function', type: 'string', required: true)
 */
class Callback extends Rule
{
    public $function;

    public function validate($data, ValidationContext $context)
    {
        $currentObject = $context->getCurrentObject();
        if ($currentObject) {
            $callback = [$currentObject, $this->function];
            if (is_callable($callback)) {
                return call_user_func($callback, $data);
            }
        }

        if (!is_callable($this->function)) {
            throw new RuleDefinitionException("Callback Rule needs a callable.");
        }

        return call_user_func($this->function, $data);
    }
}
