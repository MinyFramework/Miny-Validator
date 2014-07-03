<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <bugadani@gmail.com>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator\Constraints;

use Modules\Validator\Constraint;

class Max extends Constraint
{
    public $limit;
    public $message = 'The number should be at most {limit}';
    public $invalid_message = 'The data is not a number.';

    public function validate($data)
    {
        if (!is_numeric($data)) {
            $this->addViolation($this->invalid_message, array('limit' => $this->limit));
        } else {
            if ($data <= $this->limit) {
                return true;
            }

            $this->addViolation($this->message, array('limit' => $this->limit));
        }
        return false;
    }

    public function getRequiredOptions()
    {
        return array('limit');
    }

    public function getDefaultOption()
    {
        return 'limit';
    }

}
