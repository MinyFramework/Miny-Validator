<?php

namespace Modules\Validator\Rules;

use Miny\Event\EventDispatcher;
use Modules\Validator\RuleSet;
use Modules\Validator\ValidatorService;

class Tester
{
    public $property;

    public function testProperty()
    {
        return $this->property === 2;
    }

    static public function test($value)
    {
        return is_int($value);
    }
}

class CallbackTest extends \PHPUnit_Framework_TestCase
{
    public function testThatSimpleCallbackFunctionIsCalled()
    {
        $rule           = new Callback();
        $rule->function = 'is_int';

        $validator = new ValidatorService(new EventDispatcher());

        $this->assertTrue($validator->validateValue(2, $rule));
        $this->assertFalse($validator->validateValue('2', $rule));
    }

    public function testThatClosureIsCalled()
    {
        $rule           = new Callback();
        $rule->function = function ($value) {
            return is_int($value);
        };

        $validator = new ValidatorService(new EventDispatcher());

        $this->assertTrue($validator->validateValue(2, $rule));
        $this->assertFalse($validator->validateValue('2', $rule));
    }

    public function testThatStaticMethodCallbackFunctionIsCalled()
    {
        $rule           = new Callback();
        $rule->function = __NAMESPACE__ . '\\Tester::test';

        $validator = new ValidatorService(new EventDispatcher());

        $this->assertTrue($validator->validateValue(2, $rule));
        $this->assertFalse($validator->validateValue('2', $rule));
    }

    public function testThatMethodIsCalled()
    {
        $rule           = new Callback();
        $rule->function = 'testProperty';

        $ruleSet = new RuleSet();
        $ruleSet->property('property', $rule);

        $validator = new ValidatorService(new EventDispatcher());
        $validator->register(__NAMESPACE__ . '\\Tester', $ruleSet);

        $object = new Tester();

        $object->property = 2;
        $this->assertTrue($validator->validate($object));

        $object->property = '2';
        $this->assertFalse($validator->validate($object));
    }
}
