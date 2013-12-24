<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator\Constraints;

use Modules\Validator\Constraint;
use UnexpectedValueException;

class One extends Constraint
{
    public $constraints = array();

    public function validate($data)
    {
        if (!is_array($this->constraints)) {
            $this->constraints = array($this->constraints);
        }
        $is_valid  = true;
        $any_valid = false;
        foreach ($this->constraints as $constraint) {
            if (!$constraint instanceof Constraint) {
                throw new UnexpectedValueException('Invalid parameter set.');
            }
            if ($constraint->validate($data)) {
                if ($any_valid) {
                    $is_valid = false;
                }
                $any_valid = true;
            } else {
                $this->addViolationList($constraint->getViolationList());
            }
        }
        if ($is_valid) {
            $this->getViolationList()->clean();
        }
        return $is_valid;
    }

    public function getRequiredOptions()
    {
        return array('constraints');
    }

    public function getDefaultOption()
    {
        return 'constraints';
    }
}
