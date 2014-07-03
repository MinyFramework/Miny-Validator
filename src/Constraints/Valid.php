<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <bugadani@gmail.com>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator\Constraints;

use Modules\Validator\Constraint;

class Valid extends Constraint
{
    public $message = 'This value should be valid.';

    public function getDefaultOption()
    {
        return 'message';
    }

}
