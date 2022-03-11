<?php

namespace App\Exceptions;

use App\Enums\ErrorCodes;
use Exception;

class InvalidCredentialsException extends Exception
{
    public function render()
    {
        return response()->json(
            [
                'message' => ErrorCodes::getDescription(ErrorCodes::INVALID_CREDENTIALS),
                'error_code' => ErrorCodes::INVALID_CREDENTIALS,
            ],
            422
        );
    }
}
