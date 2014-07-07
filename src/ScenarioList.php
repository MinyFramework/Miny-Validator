<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <bugadani@gmail.com>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator;

/**
 * @Annotation
 * @DefaultAttribute scenarios
 * @Attribute('scenarios', type: {'string'})
 */
class ScenarioList
{
    public $scenarios;
}
