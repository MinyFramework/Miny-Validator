<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator\Constraints;

use Modules\Validator\Constraint;

class NotBlank extends Constraint
{
    public $message = 'This value should not be blank.';

    public function validate($data)
    {
        if (!empty($data)) {
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