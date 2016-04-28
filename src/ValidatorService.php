<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <bugadani@gmail.com>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator;

use Miny\Event\EventDispatcher;
use Modules\Validator\Events\InvalidEvent;
use Modules\Validator\Events\PostValidationEvent;
use Modules\Validator\Events\PreValidationEvent;
use Modules\Validator\Events\ValidEvent;
use Validatiny\RuleReader;
use Validatiny\Validator;

class ValidatorService extends Validator
{
    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    public function __construct(EventDispatcher $eventDispatcher, RuleReader $reader)
    {
        parent::__construct($reader);

        $this->eventDispatcher = $eventDispatcher;
    }

    public function validate($object, $forScenario = self::SCENARIO_ALL)
    {
        $this->eventDispatcher->raiseEvent(
            new PreValidationEvent($object)
        );
        if (parent::validate($object, $forScenario)) {
            $this->eventDispatcher->raiseEvent(
                new ValidEvent($object)
            );
        } else {
            $this->eventDispatcher->raiseEvent(
                new InvalidEvent($object)
            );
        }
        $this->eventDispatcher->raiseEvent(
            new PostValidationEvent($object)
        );
    }
}