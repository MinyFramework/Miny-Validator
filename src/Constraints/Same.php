<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <bugadani@gmail.com>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator\Constraints;

use Modules\Validator\Constraint;

class Same extends Constraint
{
    public $data;
    public $message = 'The data are not the same.';

    public function validate($data)
    {
        if ($data === $this->data) {
            return true;
        }

        $this->addViolation($this->message);
        return false;
    }

    public function getRequiredOptions()
    {
        return array('data');
    }

    public function getDefaultOption()
    {
        return 'data';
    }

}
