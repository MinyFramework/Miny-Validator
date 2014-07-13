<?php

namespace Modules\Validator\Rules;

use Miny\Event\EventDispatcher;
use Modules\Validator\ValidatorService;

class NotBlankTest extends \PHPUnit_Framework_TestCase
{
    public function testThatNotBlankValidatesCorrectly()
    {
        $rule = new NotBlank();

        $validator = new ValidatorService(new EventDispatcher());

        $this->assertFalse($validator->validateValue('', $rule));
        $this->assertFalse($validator->validateValue(null, $rule));
        $this->assertTrue($validator->validateValue(' ', $rule));
    }
}
