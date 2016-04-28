<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <bugadani@gmail.com>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator\Events;

use Modules\Validator\ValidationEvents;

class InvalidEvent extends ValidationEvent
{
    /**
     * @param object $object
     */
    public function __construct($object)
    {
        parent::__construct(ValidationEvents::INVALID, $object);
    }
}
