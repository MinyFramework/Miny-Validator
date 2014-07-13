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
     * @var ValidationContext
     */
    private $context;

    /**
     * @var object
     */
    private $object;

    /**
     * @param string            $name
     * @param object            $object
     * @param ValidationContext $context
     */
    public function __construct($name, $object, ValidationContext $context)
    {
        $this->object  = $object;
        $this->context = $context;
        parent::__construct($name);
    }

    /**
     * @return ValidationContext
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return object
     */
    public function getObject()
    {
        return $this->object;
    }
}
