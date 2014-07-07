<?php

namespace Modules\Validator\Rules;

use Modules\Validator\ValidatorService;

class DateTimeTest extends \PHPUnit_Framework_TestCase
{
    public function testThatDateTimeAcceptsDateTime()
    {
        $rule = new DateTime();

        $validator = new ValidatorService();

        $this->assertTrue($validator->validateValue(new \DateTime(), $rule));
    }

    public function testThatDateTimeChecksFormat()
    {
        $rule = new DateTime();

        $validator = new ValidatorService();

        $this->assertTrue($validator->validateValue('1990-05-04 09:36:12', $rule));
        $this->assertFalse($validator->validateValue('1990 05-04 09:36:12', $rule));
    }

    public function testThatDateTimeChecksValidityOfDate()
    {
        $rule = new DateTime();

        $validator = new ValidatorService();

        $this->assertTrue($validator->validateValue('1992-02-29 09:36:12', $rule));
        $this->assertFalse($validator->validateValue('1993-02-29 09:36:12', $rule));
        $this->assertFalse($validator->validateValue('1990-05-32 09:36:12', $rule));
        $this->assertFalse($validator->validateValue('1990-13-20 09:36:12', $rule));
    }
}
