<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <bugadani@gmail.com>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator\Constraints;

use Modules\Validator\Constraint;
use Modules\Validator\Exceptions\ConstraintException;

class Callback extends Constraint
{
    public $callback;
    public $message = 'The data is not valid.';

    public function __construct($params)
    {
        if (!is_callable($params)) {
            if (isset($params['callback']) && !is_callable($params['callback'])) {
                throw new ConstraintException('Callback must be callable');
            }
        } else {
            $params = array(
                'callback' => $params
            );
        }
        parent::__construct($params);
    }

    public function validate($data)
    {
        if (call_user_func($this->callback, $data)) {
            return true;
        }
        $this->addViolation($this->message);
        return false;
    }

    public function getRequiredOptions()
    {
        return array('callback');
    }

    public function getDefaultOption()
    {
        return 'callback';
    }

}
