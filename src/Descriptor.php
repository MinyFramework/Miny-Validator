<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator;

class Descriptor
{
    /**
     * @var Constraint[]
     */
    private $constraints = array();

    /**
     * @param string $name
     * @return Constraint[]
     */
    public function getConstraints($type)
    {
        if (!isset($this->constraints[$type])) {
            return array();
        }
        return $this->constraints[$type];
    }

    /**
     * @param string $name
     * @param Constraint $constraint
     * @return Constraint
     */
    public function addClassConstraint($name, Constraint $constraint)
    {
        return $this->addConstraint('class', $name, $constraint);
    }

    /**
     * @param string $name
     * @param Constraint $constraint
     * @return Constraint
     */
    public function addGetterConstraint($name, Constraint $constraint)
    {
        return $this->addConstraint('getter', $name, $constraint);
    }

    /**
     * @param string $name
     * @param Constraint $constraint
     * @return Constraint
     */
    public function addPropertyConstraint($name, Constraint $constraint)
    {
        return $this->addConstraint('property', $name, $constraint);
    }

    /**
     * @param string $type
     * @param string $name
     * @param Constraint $constraint
     * @return Constraint
     */
    private function addConstraint($type, $name, Constraint $constraint)
    {
        if (!isset($this->constraints[$type])) {
            $this->constraints[$type] = array();
        }
        if (!isset($this->constraints[$type][$name])) {
            $this->constraints[$type][$name] = array();
        }
        $this->constraints[$type][$name][] = $constraint;
        return $constraint;
    }

}
