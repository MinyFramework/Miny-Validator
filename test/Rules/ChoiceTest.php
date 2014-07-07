<?php

namespace Modules\Validator\Rules;

use Modules\Validator\ValidatorService;

class ChoiceTest extends \PHPUnit_Framework_TestCase
{
    public function testThatHardcodedElementsWork()
    {
        $rule = Choice::fromArray(
            array(
                'choices' => array(1, 2, 3)
            )
        );

        $validator = new ValidatorService();

        $this->assertTrue($validator->validateValue(1, $rule));
        $this->assertFalse($validator->validateValue(4, $rule));
    }

    public function testThatElementsFromCallbackWork()
    {
        $rule = Choice::fromArray(
            array(
                'source' => function () {
                        return array(1, 2, 3);
                    }
            )
        );

        $validator = new ValidatorService();

        $this->assertTrue($validator->validateValue(1, $rule));
        $this->assertFalse($validator->validateValue(4, $rule));
    }

    public function testThatMultipleChoicesWork()
    {
        $rule = Choice::fromArray(
            array(
                'choices'  => array(1, 2, 3),
                'multiple' => true
            )
        );

        $validator = new ValidatorService();

        $this->assertTrue($validator->validateValue(1, $rule));
        $this->assertTrue($validator->validateValue(array(1, 2), $rule));
        $this->assertFalse($validator->validateValue(4, $rule));
        $this->assertFalse($validator->validateValue(array(2, 4), $rule));
    }
}
