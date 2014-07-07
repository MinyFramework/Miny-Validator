<?php

namespace Modules\Validator\Rules;

use Modules\Validator\ValidatorService;

class NullTest extends \PHPUnit_Framework_TestCase
{
    public function testThatNullValidatesCorrectly()
    {
        $rule = new Null();

        $validator = new ValidatorService();

        $this->assertTrue($validator->validateValue(null, $rule));
        $this->assertFalse($validator->validateValue(false, $rule));
        $this->assertFalse($validator->validateValue('', $rule));
    }
}
