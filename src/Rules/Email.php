<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <bugadani@gmail.com>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator\Rules;

use Modules\Validator\Rule;
use Modules\Validator\ValidationContext;

/**
 * @Annotation
 */
class Email extends Rule
{
    public $check_mx = false;
    public $message = '{address} is is not a valid e-mail address.';

    public function validate($data, ValidationContext $context)
    {
        if (!is_scalar($data)) {
            if (!is_object($data) || !method_exists($data, '__toString')) {
                throw new \InvalidArgumentException('The Email Rule expects a string type.');
            }
        }
        $data = (string)$data;
        if (filter_var($data, FILTER_VALIDATE_EMAIL)) {
            if (!$this->check_mx) {
                return true;
            }
            $domain = substr($data, strpos($data, '@') + 1);
            if (checkdnsrr($domain . '.', 'MX')) {
                return true;
            }
        }

        return false;
    }

    public function getMessage($value, ValidationContext $context)
    {
        return str_replace('{address}', $value, $this->message);
    }
}
