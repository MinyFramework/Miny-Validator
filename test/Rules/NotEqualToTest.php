<?php
/**
 * Created by PhpStorm.
 * User: Buga
 * Date: 2014.07.07.
 * Time: 21:14
 */

namespace Modules\Validator\Rules;


use Modules\Validator\ValidatorService;

class NotEqualToTest extends \PHPUnit_Framework_TestCase
{
    public function testThatNotEqualToValidatesCorrectly()
    {
        $rule       = new NotEqualTo();
        $rule->data = 5;

        $validator = new ValidatorService();

        $this->assertTrue($validator->validateValue(4, $rule));
        $this->assertFalse($validator->validateValue(5, $rule));
        $this->assertFalse($validator->validateValue('5', $rule));
        $this->assertTrue($validator->validateValue(6, $rule));
    }
}
