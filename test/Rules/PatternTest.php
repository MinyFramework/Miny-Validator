<?php

namespace Modules\Validator\Rules;

use Miny\Event\EventDispatcher;
use Modules\Validator\ValidatorService;

class PatternTest extends \PHPUnit_Framework_TestCase
{
    public function testThatPatternValidatesCorrectly()
    {
        $rule          = new Pattern();
        $rule->pattern = '/\d{2}\.\d/';

        $validator = new ValidatorService(new EventDispatcher());

        $this->assertTrue($validator->validateValue('12.3', $rule));
        $this->assertFalse($validator->validateValue('123', $rule));
    }
}
