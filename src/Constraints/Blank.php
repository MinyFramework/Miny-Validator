<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <bugadani@gmail.com>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator\Constraints;

use Modules\Validator\Constraint;

class Blank extends Constraint
{
    public $message = 'This value should be blank.';

    public function validate($data)
    {
        if (empty($data)) {
            return true;
        }

        $this->addViolation($this->message);
        return false;
    }

    public function getDefaultOption()
    {
        return 'message';
    }

}
