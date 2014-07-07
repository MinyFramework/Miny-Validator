<?php

namespace Modules\Validator\Rules;

use Modules\Validator\ValidatorService;

class GreaterThanTest extends \PHPUnit_Framework_TestCase
{
    public function testThatGreaterThanValidatesCorrectly()
    {
        $rule       = new GreaterThan();
        $rule->data = 5;

        $validator = new ValidatorService();

        $this->assertFalse($validator->validateValue(4, $rule));
        $this->assertFalse($validator->validateValue(5, $rule));
        $this->assertTrue($validator->validateValue(6, $rule));
    }
}
