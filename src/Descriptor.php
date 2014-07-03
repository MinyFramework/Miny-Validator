<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <bugadani@gmail.com>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator;

class Descriptor
{
    const PROPERTY_CONSTRAINT = 'property';
    const GETTER_CONSTRAINT   = 'getter';
    const CLASS_CONSTRAINT    = 'class';

    /**
     * @var Constraint[]
     */
    private $constraints = array();

    public function __construct()
    {
        $this->constraints = array(
            self::PROPERTY_CONSTRAINT => array(),
            self::GETTER_CONSTRAINT   => array(),
            self::CLASS_CONSTRAINT    => array()
        );
    }


    /**
     * @param $type
     *
     * @return Constraint[]
     */
    public function getConstraints($type)
    {
        return $this->constraints[$type];
    }

    /**
     * @param string     $name
     * @param Constraint $constraint
     *
     * @return Constraint
     */
    public function addClassConstraint($name, Constraint $constraint)
    {
        return $this->addConstraint(self::CLASS_CONSTRAINT, $name, $constraint);
    }

    /**
     * @param string     $name
     * @param Constraint $constraint
     *
     * @return Constraint
     */
    public function addGetterConstraint($name, Constraint $constraint)
    {
        return $this->addConstraint(self::GETTER_CONSTRAINT, $name, $constraint);
    }

    /**
     * @param string     $name
     * @param Constraint $constraint
     *
     * @return Constraint
     */
    public function addPropertyConstraint($name, Constraint $constraint)
    {
        return $this->addConstraint(self::PROPERTY_CONSTRAINT, $name, $constraint);
    }

    /**
     * @param string     $type
     * @param string     $name
     * @param Constraint $constraint
     *
     * @return Constraint
     */
    private function addConstraint($type, $name, Constraint $constraint)
    {
        if (!isset($this->constraints[$type][$name])) {
            $this->constraints[$type][$name] = array();
        }
        $this->constraints[$type][$name][] = $constraint;

        return $constraint;
    }

}
