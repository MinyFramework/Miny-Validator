<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
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