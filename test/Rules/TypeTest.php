<?php

namespace Modules\Validator\Rules;

use Modules\Validator\ValidatorService;

class TypeTest extends \PHPUnit_Framework_TestCase
{
    public function testThatIsFunctionsWork()
    {
        $rule       = new Type();
        $rule->type = 'string';

        $validator = new ValidatorService();

        $this->assertTrue($validator->validateValue('string', $rule));
        $this->assertFalse($validator->validateValue(1, $rule));
    }

    public function testThatCtypeFunctionsWork()
    {
        $rule       = new Type();
        $rule->type = 'alpha';

        $validator = new ValidatorService();

        $this->assertTrue($validator->validateValue('string', $rule));
        $this->assertFalse($validator->validateValue('asd123', $rule));
    }

    public function testThatClassTypesWork()
    {
        $rule       = new Type();
        $rule->type = 'stdClass';

        $validator = new ValidatorService();

        $this->assertTrue($validator->validateValue(new \stdClass(), $rule));
        $this->assertFalse($validator->validateValue('asd123', $rule));
    }
}
