<?php

namespace Modules\Validator\Rules;

use Miny\Event\EventDispatcher;
use Modules\Validator\ValidatorService;

class FalseTest extends \PHPUnit_Framework_TestCase
{
    public function testThatFalseValidatesCorrectly()
    {
        $rule = new False();

        $validator = new ValidatorService(new EventDispatcher());

        $this->assertTrue($validator->validateValue('0', $rule));
        $this->assertTrue($validator->validateValue(false, $rule));
        $this->assertTrue($validator->validateValue(0, $rule));
        $this->assertFalse($validator->validateValue(' ', $rule));
        $this->assertFalse($validator->validateValue('1', $rule));
    }
}
