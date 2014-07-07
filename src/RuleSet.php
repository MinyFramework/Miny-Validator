<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <bugadani@gmail.com>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator;

class RuleSet
{
    private $scenarios = array();
    private $scenarioList = array('default');

    public function setScenarioList($scenarios)
    {
        $this->scenarioList = (array)$scenarios;
    }

    public function getScenarioList()
    {
        return $this->scenarioList;
    }

    public function property($name, Rule $rule)
    {
        $this->add($name, $rule, 'property');
    }

    public function getter($name, Rule $rule)
    {
        $this->add($name, $rule, 'getter');
    }

    public function add($name, Rule $rule, $type)
    {
        foreach ((array)$rule->for as $scenario) {
            if (!isset($this->scenarios[$scenario])) {
                $this->scenarios[$scenario] = array(
                    'property' => array(),
                    'getter'   => array()
                );
            }
            if (!isset($this->scenarios[$scenario][$type][$name])) {
                $this->scenarios[$scenario][$type][$name] = array();
            }
            $this->scenarios[$scenario][$type][$name][] = $rule;
        }
    }

    /**
     * @param string $scenario
     *
     * @throws \OutOfBoundsException
     *
     * @return array
     */
    public function getRulesForScenario($scenario)
    {
        if (!$this->scenarios[$scenario]) {
            throw new \OutOfBoundsException("Scenario {$scenario} is not defined.");
        }

        return $this->scenarios[$scenario];
    }
}
