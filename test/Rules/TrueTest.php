<?php

namespace Modules\Validator\Rules;

use Miny\Event\EventDispatcher;
use Modules\Validator\ValidatorService;

class TrueTest extends \PHPUnit_Framework_TestCase
{
    public function testThatTrueValidatesCorrectly()
    {
        $rule = new True();

        $validator = new ValidatorService(new EventDispatcher());

        $this->assertTrue($validator->validateValue('1', $rule));
        $this->assertTrue($validator->validateValue(true, $rule));
        $this->assertTrue($validator->validateValue(1, $rule));
        $this->assertFalse($validator->validateValue(' ', $rule));
        $this->assertFalse($validator->validateValue('0', $rule));
    }
}
