<?php

namespace Modules\Validator\Rules;

use Modules\Validator\ValidatorService;

class DateTest extends \PHPUnit_Framework_TestCase
{
    public function testThatDateAcceptsDateTime()
    {
        $rule = new Date();

        $validator = new ValidatorService();

        $this->assertTrue($validator->validateValue(new \DateTime(), $rule));
    }

    public function testThatDateChecksFormat()
    {
        $rule = new Date();

        $validator = new ValidatorService();

        $this->assertTrue($validator->validateValue('1990-05-04', $rule));
        $this->assertFalse($validator->validateValue('1990 05-04', $rule));
    }

    public function testThatDateChecksValidity()
    {
        $rule = new Date();

        $validator = new ValidatorService();

        $this->assertTrue($validator->validateValue('1992-02-29', $rule));
        $this->assertFalse($validator->validateValue('1993-02-29', $rule));
        $this->assertFalse($validator->validateValue('1990-05-32', $rule));
        $this->assertFalse($validator->validateValue('1990-13-20', $rule));
    }
}
