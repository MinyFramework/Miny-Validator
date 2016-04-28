<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <bugadani@gmail.com>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator\Events;

use Miny\Event\Event;
use Modules\Validator\ValidationContext;

abstract class ValidationEvent extends Event
{

    /**
     * @var object
     */
    private $object;

    /**
     * @param string            $name
     * @param object            $object
     */
    public function __construct($name, $object)
    {
        $this->object  = $object;

        parent::__construct($name);
    }

    /**
     * @return object
     */
    public function getObject()
    {
        return $this->object;
    }
}
