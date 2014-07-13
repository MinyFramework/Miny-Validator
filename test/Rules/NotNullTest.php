<?php

namespace Modules\Validator\Rules;

use Miny\Event\EventDispatcher;
use Modules\Validator\ValidatorService;

class NotNullTest extends \PHPUnit_Framework_TestCase
{
    public function testThatNotNullValidatesCorrectly()
    {
        $rule = new NotNull();

        $validator = new ValidatorService(new EventDispatcher());

        $this->assertFalse($validator->validateValue(null, $rule));
        $this->assertTrue($validator->validateValue(false, $rule));
        $this->assertTrue($validator->validateValue('', $rule));
    }
}
