<?php

namespace Modules\Validator\Rules;

use Modules\Validator\ValidatorService;

class LengthTest extends \PHPUnit_Framework_TestCase
{
    public function testThatMinimumValueIsChecked()
    {
        $rule             = new Length();
        $rule->min        = 2;
        $rule->minMessage = 'Value should be at least {min} characters long.';

        $validator = new ValidatorService();

        $this->assertTrue($validator->validateValue('ab', $rule));
        $this->assertFalse($validator->validateValue('a', $rule));

        $this->assertEquals(
            array('Value should be at least 2 characters long.'),
            $validator->getErrors()->get()
        );
    }

    public function testThatMaximumValueIsChecked()
    {
        $rule             = new Length();
        $rule->max        = 2;
        $rule->maxMessage = 'Value should be at most {max} characters long.';

        $validator = new ValidatorService();

        $this->assertTrue($validator->validateValue('ab', $rule));
        $this->assertFalse($validator->validateValue('abc', $rule));

        $this->assertEquals(
            array('Value should be at most 2 characters long.'),
            $validator->getErrors()->get()
        );
    }

    public function testThatValueRangeIsChecked()
    {
        $rule             = new Length();
        $rule->min        = 2;
        $rule->max        = 3;
        $rule->minMessage = 'Value should be at least {min} characters long.';
        $rule->maxMessage = 'Value should be at most {max} characters long.';

        $validator = new ValidatorService();

        $this->assertTrue($validator->validateValue('ab', $rule));
        $this->assertFalse($validator->validateValue('abcd', $rule));

        $this->assertEquals(
            array('Value should be at most 3 characters long.'),
            $validator->getErrors()->get()
        );

        $this->assertFalse($validator->validateValue('a', $rule));

        $this->assertEquals(
            array('Value should be at least 2 characters long.'),
            $validator->getErrors()->get()
        );
    }

    public function testThatExactValueIsChecked()
    {
        $rule               = new Length();
        $rule->min          = 2;
        $rule->max          = 2;
        $rule->exactMessage = 'Value should be exactly {min} characters long.';

        $validator = new ValidatorService();

        $this->assertTrue($validator->validateValue('ab', $rule));
        $this->assertFalse($validator->validateValue('abc', $rule));

        $this->assertEquals(
            array('Value should be exactly 2 characters long.'),
            $validator->getErrors()->get()
        );
    }
}
