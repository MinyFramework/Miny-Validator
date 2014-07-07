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
class Length extends Rule
{
    public $min = 0;
    public $max = PHP_INT_MAX;
    public $minMessage = 'This property should be at least {min} characters long.';
    public $maxMessage = 'This property should be at most {max} characters long.';
    public $exactMessage = 'This property should be exactly {min} characters long.';
    private $charset = 'UTF-8';

    public function validate($data, ValidationContext $context)
    {
        if (!is_scalar($data)) {
            if (!is_object($data) || !method_exists($data, '__toString')) {
                throw new \InvalidArgumentException('The Length Rule expects a string type.');
            }
        }
        $string = (string)$data;

        if (function_exists('grapheme_strlen') && $this->charset === 'UTF-8') {
            $length = grapheme_strlen($string);
        } elseif (function_exists('mb_strlen')) {
            $length = mb_strlen($string, $this->charset);
        } else {
            $length = strlen($string);
        }

        if ($this->min === $this->max) {
            if ($length !== $this->min) {
                $this->message = $this->exactMessage;

                return false;
            }
        } elseif ($length < $this->min) {
            $this->message = $this->minMessage;

            return false;
        } elseif ($length > $this->max) {
            $this->message = $this->maxMessage;

            return false;
        }

        return true;
    }

    public function getMessage($value, ValidationContext $context)
    {
        $message = parent::getMessage($value, $context);
        $message = str_replace(array('{min}', '{max}'), array($this->min, $this->max), $message);

        return $message;
    }
}
