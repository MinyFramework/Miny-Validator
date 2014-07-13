<?php

namespace Modules\Validator\Rules;

use Miny\Event\EventDispatcher;
use Modules\Validator\ValidatorService;

class AllTest extends \PHPUnit_Framework_TestCase
{
    public function testThatAllValidatesCorrectly()
    {
        $rule = All::fromArray(
            array(
                'rules' => array(
                    Type::fromArray(array('type' => 'int')),
                    Range::fromArray(array('min' => 5, 'max' => 10)),
                )
            )
        );

        $validator = new ValidatorService(new EventDispatcher());

        $this->assertTrue($validator->validateValue(array(5, 6, 7), $rule));
        $this->assertFalse($validator->validateValue(array('not int' => 'a'), $rule, 'name'));

        $this->assertEquals(
            array(
                'This property has an invalid type.',
                'This property should be between 5 and 10.'
            ),
            $validator->getErrors()->get('name[not int]')
        );
    }

    public function testThatAllRespectsGroupOption()
    {
        $rule = All::fromArray(
            array(
                'rules' => array(
                    Type::fromArray(array('type' => 'int')),
                    Range::fromArray(array('min' => 5, 'max' => 10, 'for' => 'foobar')),
                )
            )
        );

        $validator = new ValidatorService(new EventDispatcher());

        $this->assertTrue($validator->validateValue(array(5, 6, 15), $rule));
        $this->assertFalse($validator->validateValue(array('not int' => 'a'), $rule));
    }
}
