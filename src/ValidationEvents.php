<?php

namespace Modules\Validator;


class ValidationEvents
{
    const PRE_VALIDATION  = 'onPreValidate';
    const POST_VALIDATION = 'onPostValidate';
    const VALID           = 'onValid';
    const INVALID         = 'onInvalid';
}
