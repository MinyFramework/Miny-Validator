<?php

namespace Modules\Validator\Rules;

use Modules\Validator\ValidatorService;

class GreaterThanOrEqualTest extends \PHPUnit_Framework_TestCase
{
    public function testThatGreaterThanOrEqualValidatesCorrectly()
    {
        $rule       = new GreaterThanOrEqual();
        $rule->data = 5;

        $validator = new ValidatorService();

        $this->assertFalse($validator->validateValue(4, $rule));
        $this->assertTrue($validator->validateValue(5, $rule));
        $this->assertTrue($validator->validateValue(6, $rule));
    }
}
