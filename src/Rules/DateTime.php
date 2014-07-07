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
class DateTime extends Rule
{
    public $message = '{value} is not a valid date and time.';

    public function validate($data, ValidationContext $context)
    {
        if ($data instanceof \DateTime) {
            return true;
        }
        if (!is_scalar($data)) {
            if (!is_object($data) || !method_exists($data, '__toString')) {
                throw new \InvalidArgumentException('The DateTime Rule expects a DateTime object or a string.');
            }
        }
        if (preg_match(
            '/^(\d{4})-(\d{2})-(\d{2}) (0\d|1\d|2[0-3]):([0-5][0-9]):([0-5][0-9])$/',
            (string)$data,
            $match
        )
        ) {
            if (checkdate($match[2], $match[3], $match[1])) {
                return true;
            }
        }

        return false;
    }
}
