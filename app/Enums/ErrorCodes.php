<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static INVALID_CREDENTIALS
 * @method static USER_NOT_FOUND
 */
final class ErrorCodes extends Enum
{
    /**
     * When email address and/or password is incorrect
     */
    const INVALID_CREDENTIALS = 'INVALID_CREDENTIALS';

    /**
     * When email address is not existing
     */
    const USER_NOT_FOUND = 'NOT_FOUND';


}
