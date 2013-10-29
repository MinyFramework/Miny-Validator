<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator;

use Miny\Application\BaseApplication;

class Module extends \Miny\Application\Module
{
    public function init(BaseApplication $app)
    {
        $app->add('validator', '\Modules\Validator\Validator');
    }

}
