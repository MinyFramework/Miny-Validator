<?php

namespace Modules\Validator\Rules;

use Modules\Validator\ValidatorService;

class OptionalTest extends \PHPUnit_Framework_TestCase
{
    public function testThatTrueValidatesCorrectly()
    {
        $rule        = new Optional();
        $rule->rules = array(
            new True
        );

        $validator = new ValidatorService();

        $this->assertTrue($validator->validateValue(null, $rule));
        $this->assertTrue($validator->validateValue('', $rule));
        $this->assertTrue($validator->validateValue(true, $rule));
        $this->assertFalse($validator->validateValue(false, $rule));
        $this->assertFalse($validator->validateValue('foobar', $rule));
    }
}
