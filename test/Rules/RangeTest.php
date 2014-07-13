<?php

namespace Modules\Validator\Rules;

use Miny\Event\EventDispatcher;
use Modules\Validator\ValidatorService;

class RangeTest extends \PHPUnit_Framework_TestCase
{
    public function testThatRangeValidatesCorrectly()
    {
        $rule = Range::fromArray(array('min' => 3, 'max' => 4));

        $validator = new ValidatorService(new EventDispatcher());

        $this->assertTrue($validator->validateValue(3, $rule));
        $this->assertTrue($validator->validateValue(4, $rule));
        $this->assertFalse($validator->validateValue(5, $rule));
    }
}
