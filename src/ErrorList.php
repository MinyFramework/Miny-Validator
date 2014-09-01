<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <bugadani@gmail.com>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\Validator;

use ArrayIterator;
use IteratorAggregate;

class ErrorList implements IteratorAggregate
{
    private $errors = [];

    public function add($property, $message)
    {
        if ($property === null) {
            $this->errors[] = $message;
        } else {
            if (!isset($this->errors[$property])) {
                $this->errors[$property] = [];
            }
            $this->errors[$property][] = $message;
        }
    }

    public function get($property = null)
    {
        if ($property === null) {
            return $this->errors;
        }
        if (!isset($this->errors[$property])) {
            return [];
        }

        return $this->errors[$property];
    }

    public function getIterator()
    {
        return new ArrayIterator($this->errors);
    }
}
