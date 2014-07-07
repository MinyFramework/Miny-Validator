<?php
/**
 * Created by PhpStorm.
 * User: Buga
 * Date: 2014.07.07.
 * Time: 21:14
 */

namespace Modules\Validator\Rules;


use Modules\Validator\ValidatorService;

class IdenticalToTest extends \PHPUnit_Framework_TestCase
{
    public function testThatIdenticalToValidatesCorrectly()
    {
        $rule       = new IdenticalTo();
        $rule->data = 5;

        $validator = new ValidatorService();

        $this->assertFalse($validator->validateValue(4, $rule));
        $this->assertTrue($validator->validateValue(5, $rule));
        $this->assertFalse($validator->validateValue('5', $rule));
        $this->assertFalse($validator->validateValue(6, $rule));
    }
}
