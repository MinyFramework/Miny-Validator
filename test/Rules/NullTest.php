<?php

namespace Modules\Validator\Rules;

use Miny\Event\EventDispatcher;
use Modules\Validator\ValidatorService;

class NullTest extends \PHPUnit_Framework_TestCase
{
    public function testThatNullValidatesCorrectly()
    {
        $rule = new Null();

        $validator = new ValidatorService(new EventDispatcher());

        $this->assertTrue($validator->validateValue(null, $rule));
        $this->assertFalse($validator->validateValue(false, $rule));
        $this->assertFalse($validator->validateValue('', $rule));
    }
}
