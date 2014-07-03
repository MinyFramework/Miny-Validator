<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <bugadani@gmail.com>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator\Constraints;

use Modules\Validator\Constraint;

class Email extends Constraint
{
    public $message = 'The e-mail address "{address}" is not valid.';
    public $check_mx = false;

    public function validate($data)
    {
        if (filter_var($data, FILTER_VALIDATE_EMAIL)) {
            if (!$this->check_mx) {
                return true;
            }
            $domain = substr($data, strpos('@', $data) + 1);
            if (checkdnsrr($domain . '.', 'MX')) {
                return true;
            }
        }

        $this->addViolation($this->message, array('address' => $data));
        return false;
    }

}
