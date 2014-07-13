<?php

namespace Modules\Validator\Rules;

use Miny\Event\EventDispatcher;
use Modules\Validator\ValidatorService;

class LessThanTest extends \PHPUnit_Framework_TestCase
{
    public function testThatLessThanValidatesCorrectly()
    {
        $rule       = new LessThan();
        $rule->data = 5;

        $validator = new ValidatorService(new EventDispatcher());

        $this->assertTrue($validator->validateValue(4, $rule));
        $this->assertTrue($validator->validateValue(3, $rule));
        $this->assertFalse($validator->validateValue(5, $rule));
    }
}
