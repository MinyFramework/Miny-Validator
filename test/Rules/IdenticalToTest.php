<?php

namespace Modules\Validator\Rules;

use Miny\Event\EventDispatcher;
use Modules\Validator\ValidatorService;

class IdenticalToTest extends \PHPUnit_Framework_TestCase
{
    public function testThatIdenticalToValidatesCorrectly()
    {
        $rule       = new IdenticalTo();
        $rule->data = 5;

        $validator = new ValidatorService(new EventDispatcher());

        $this->assertFalse($validator->validateValue(4, $rule));
        $this->assertTrue($validator->validateValue(5, $rule));
        $this->assertFalse($validator->validateValue('5', $rule));
        $this->assertFalse($validator->validateValue(6, $rule));
    }
}
