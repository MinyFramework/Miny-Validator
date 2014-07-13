<?php

namespace Modules\Validator\Rules;

use Miny\Event\EventDispatcher;
use Modules\Validator\ValidatorService;

class EqualToTest extends \PHPUnit_Framework_TestCase
{
    public function testThatEqualToValidatesCorrectly()
    {
        $rule       = new EqualTo();
        $rule->data = 5;

        $validator = new ValidatorService(new EventDispatcher());

        $this->assertFalse($validator->validateValue(4, $rule));
        $this->assertTrue($validator->validateValue(5, $rule));
        $this->assertTrue($validator->validateValue('5', $rule));
        $this->assertFalse($validator->validateValue(6, $rule));
    }
}
