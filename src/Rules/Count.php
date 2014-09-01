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
 * @Attribute('min', type: 'int')
 * @Attribute('max', type: 'int')
 */
class Count extends Rule
{
    public $min = 0;
    public $max = PHP_INT_MAX;
    public $minMessage = 'This property should have at least {min} elements.';
    public $maxMessage = 'This property should have at most {max} elements.';
    public $exactMessage = 'This property should have exactly {min} elements.';

    public function validate($data, ValidationContext $context)
    {
        if (!is_array($data) && !$data instanceof \Countable) {
            throw new \InvalidArgumentException('The Count Rule expects an array or countable object.');
        }
        $count = count($data);
        if ($this->min === $this->max) {
            if ($count !== $this->min) {
                $this->message = $this->exactMessage;

                return false;
            }
        } elseif ($count < $this->min) {
            $this->message = $this->minMessage;

            return false;
        } elseif ($count > $this->max) {
            $this->message = $this->maxMessage;

            return false;
        }

        return true;
    }

    public function getMessage($value, ValidationContext $context)
    {
        $message = parent::getMessage($value, $context);
        $message = str_replace(['{min}', '{max}'], [$this->min, $this->max], $message);

        return $message;
    }
}
