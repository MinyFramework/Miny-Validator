<?php

namespace Modules\Validator\Rules;

use Miny\Event\EventDispatcher;
use Modules\Validator\ValidatorService;

class CountTest extends \PHPUnit_Framework_TestCase
{
    public function testThatMinimumValueIsChecked()
    {
        $rule             = new Count();
        $rule->min        = 2;
        $rule->minMessage = 'Value should have at least {min} elements.';

        $validator = new ValidatorService(new EventDispatcher());

        $this->assertTrue($validator->validateValue(array(1, 2), $rule));
        $this->assertFalse($validator->validateValue(array(1), $rule));

        $this->assertEquals(
            array('Value should have at least 2 elements.'),
            $validator->getErrors()->get()
        );
    }

    public function testThatMaximumValueIsChecked()
    {
        $rule             = new Count();
        $rule->max        = 2;
        $rule->maxMessage = 'Value should have at most {max} elements.';

        $validator = new ValidatorService(new EventDispatcher());

        $this->assertTrue($validator->validateValue(array(1, 2), $rule));
        $this->assertFalse($validator->validateValue(array(1, 2, 3), $rule));

        $this->assertEquals(
            array('Value should have at most 2 elements.'),
            $validator->getErrors()->get()
        );
    }

    public function testThatValueRangeIsChecked()
    {
        $rule = Count::fromArray(
            array(
                'min'        => 2,
                'max'        => 3,
                'minMessage' => 'Value should have at least {min} elements.',
                'maxMessage' => 'Value should have at most {max} elements.'
            )
        );

        $validator = new ValidatorService(new EventDispatcher());

        $this->assertTrue($validator->validateValue(array(1, 2), $rule));
        $this->assertFalse($validator->validateValue(array(1, 2, 3, 4), $rule));

        $this->assertEquals(
            array('Value should have at most 3 elements.'),
            $validator->getErrors()->get()
        );

        $this->assertFalse($validator->validateValue(array(1), $rule));

        $this->assertEquals(
            array('Value should have at least 2 elements.'),
            $validator->getErrors()->get()
        );
    }

    public function testThatExactValueIsChecked()
    {
        $rule               = new Count();
        $rule->min          = 2;
        $rule->max          = 2;
        $rule->exactMessage = 'Value should have exactly {min} elements.';

        $validator = new ValidatorService(new EventDispatcher());

        $this->assertTrue($validator->validateValue(array(1, 2), $rule));
        $this->assertFalse($validator->validateValue(array(1, 2, 3), $rule));

        $this->assertEquals(
            array('Value should have exactly 2 elements.'),
            $validator->getErrors()->get()
        );
    }
}
