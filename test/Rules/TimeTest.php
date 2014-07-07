<?php

namespace Modules\Validator\Rules;

use Modules\Validator\ValidatorService;

class TimeTest extends \PHPUnit_Framework_TestCase
{
    public function testThatTimeAcceptsDateTime()
    {
        $rule = new Time();

        $validator = new ValidatorService();

        $this->assertTrue($validator->validateValue(new \DateTime(), $rule));
    }

    public function testThatTimeChecksFormat()
    {
        $rule = new Time();

        $validator = new ValidatorService();

        $this->assertTrue($validator->validateValue('09:36:12', $rule));
        $this->assertFalse($validator->validateValue('9:36:12', $rule));
    }
}
