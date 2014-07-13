<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <bugadani@gmail.com>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator\Events;

use Modules\Validator\ValidationContext;
use Modules\Validator\ValidationEvents;

class ValidEvent extends ValidationEvent
{
    /**
     * @param object            $object
     * @param ValidationContext $context
     */
    public function __construct($object, ValidationContext $context)
    {
        parent::__construct(ValidationEvents::VALID, $object, $context);
    }
}
