<?php

namespace Modules\Validator\Rules;

use Modules\Validator\ValidatorService;

class EmailTest extends \PHPUnit_Framework_TestCase
{
    public function testThatEmailValidatesCorrectlyWithoutMX()
    {
        $rule = new Email();

        $validator = new ValidatorService();

        $this->assertTrue($validator->validateValue('foo@bar.asd', $rule));
    }

    public function testThatEmailValidatesCorrectlyWithMX()
    {
        $rule           = new Email();
        $rule->check_mx = true;

        $validator = new ValidatorService();

        $this->assertTrue($validator->validateValue('bugadani@gmail.com', $rule));
        $this->assertFalse($validator->validateValue('foo@bar.asd', $rule));
    }
}
