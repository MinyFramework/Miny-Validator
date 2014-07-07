<?php

namespace Modules\Validator\Rules;

use Modules\Validator\RuleSet;
use Modules\Validator\Validable;
use Modules\Validator\ValidatorService;

class TestClass implements Validable
{
    public $foo;
    public $bar = true;

    /**
     * @return RuleSet
     */
    public function getValidationInfo()
    {
        $ruleSet = new RuleSet();

        $ruleSet->property('foo', True::fromArray(array('for' => 'default')));
        $ruleSet->property('bar', False::fromArray(array('for' => 'scenario')));

        return $ruleSet;
    }
}

class ValidTest extends \PHPUnit_Framework_TestCase
{
    public function testThatValidValidatesCorrectly()
    {
        $rule      = new Valid();
        $validator = new ValidatorService();

        $testClass      = new TestClass;
        $testClass->foo = true;

        $this->assertTrue($validator->validateValue($testClass, $rule));
        $testClass->foo = false;
        $this->assertFalse($validator->validateValue($testClass, $rule));
    }
}
