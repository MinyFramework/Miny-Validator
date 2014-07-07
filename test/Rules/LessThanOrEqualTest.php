<?php

namespace Modules\Validator\Rules;

use Modules\Validator\ValidatorService;

class LessThanOrEqualTest extends \PHPUnit_Framework_TestCase
{
    public function testThatLessThanValidatesCorrectly()
    {
        $rule       = new LessThanOrEqual();
        $rule->data = 5;

        $validator = new ValidatorService();

        $this->assertTrue($validator->validateValue(4, $rule));
        $this->assertTrue($validator->validateValue(5, $rule));
        $this->assertFalse($validator->validateValue(6, $rule));
    }
}
