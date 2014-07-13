<?php

namespace Modules\Validator\Rules;

use Miny\Event\EventDispatcher;
use Modules\Validator\ValidatorService;

class BlankTest extends \PHPUnit_Framework_TestCase
{
    public function testThatBlankValidatesCorrectly()
    {
        $rule = new Blank();

        $validator = new ValidatorService(new EventDispatcher());

        $this->assertTrue($validator->validateValue('', $rule));
        $this->assertTrue($validator->validateValue(null, $rule));
        $this->assertFalse($validator->validateValue(' ', $rule));
    }
}
