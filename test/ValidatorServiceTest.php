<?php

namespace Modules\Validator;

use Miny\Event\EventDispatcher;
use Modules\Annotation\AnnotationReader;

class TestClass extends \stdClass
{
    /**
     * @TestRule('foo')
     * @TestRule('bar', for: 'scenario')
     */
    public $fooProperty;
}

/**
 * @Annotation
 * @DefaultAttribute value
 * @Attribute('value')
 * @Target('property')
 */
class TestRule extends Rule
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function validate($data, ValidationContext $context)
    {
        return $data === $this->value;
    }
}

class ValidatorServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var ValidatorService
     */
    private $service;

    public function setUp()
    {
        $this->eventDispatcher = new EventDispatcher();
        $this->service         = new ValidatorService($this->eventDispatcher);
    }

    public function testValidationUsingExternalRuleSet()
    {
        $ruleSet = new RuleSet();

        $rule1      = new TestRule('foo');
        $rule1->for = 'default';

        $rule2      = new TestRule('bar');
        $rule2->for = 'scenario';

        $ruleSet->property('fooProperty', $rule1);
        $ruleSet->property('fooProperty', $rule2);

        $object1              = new \stdClass();
        $object1->fooProperty = 'foo';

        $object2              = new \stdClass();
        $object2->fooProperty = 'bar';

        $this->assertTrue($this->service->validate($object1, null, $ruleSet));
        $this->assertTrue($this->service->validate($object2, 'scenario', $ruleSet));
        $this->assertFalse($this->service->validate($object1, 'scenario', $ruleSet));
        $this->assertFalse($this->service->validate($object2, null, $ruleSet));
    }

    public function testValidationUsingRuleSetFromAnnotation()
    {
        $annotations = new AnnotationReader();

        $this->service->setAnnotationReader($annotations);

        $object1              = new TestClass();
        $object1->fooProperty = 'foo';

        $object2              = new TestClass();
        $object2->fooProperty = 'bar';

        $this->assertTrue($this->service->validate($object1));
        $this->assertTrue($this->service->validate($object2, 'scenario'));
        $this->assertFalse($this->service->validate($object1, 'scenario'));
        $this->assertFalse($this->service->validate($object2));

        $errorList = $this->service->getErrors();
        $this->assertEquals(array('This value is invalid'), $errorList->get('fooProperty'));
    }
}
