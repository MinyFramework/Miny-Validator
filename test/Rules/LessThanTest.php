<?php

namespace Modules\Validator\Rules;

use Modules\Validator\ValidatorService;

class LessThanTest extends \PHPUnit_Framework_TestCase
{
    public function testThatLessThanValidatesCorrectly()
    {
        $rule       = new LessThan();
        $rule->data = 5;

        $validator = new ValidatorService();

        $this->assertTrue($validator->validateValue(4, $rule));
        $this->assertTrue($validator->validateValue(3, $rule));
        $this->assertFalse($validator->validateValue(5, $rule));
    }
}
