<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <bugadani@gmail.com>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator;

interface ScenarioListProvider
{
    /**
     * @return string[]
     */
    public function getScenarioList();
}
