<?php

namespace App\Exceptions;

use App\Enums\ErrorCodes;
use Exception;

class UserNotFoundException extends Exception
{
    public function render()
    {
        return response()->json(
            [
                'message' => ErrorCodes::getDescription(ErrorCodes::USER_NOT_FOUND),
                'error_code' => ErrorCodes::USER_NOT_FOUND,
            ],
            404
        );
    }
}
