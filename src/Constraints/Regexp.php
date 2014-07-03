<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <bugadani@gmail.com>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator\Constraints;

use Modules\Validator\Constraint;

class Regexp extends Constraint
{
    public $pattern;
    public $match = true;

    public function validate($data)
    {
        $pattern = str_replace('#', '\#', $this->pattern);
        if (preg_match('#^' . $pattern . '$#mux', $data)) {
            return true;
        }

        $this->addViolation($this->message);
        return false;
    }

    public function getRequiredOptions()
    {
        return array('pattern');
    }

    public function getDefaultOption()
    {
        return 'pattern';
    }

}
