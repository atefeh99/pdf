<?php

namespace App\Exceptions;

use App\Http\Controllers\ApiController;
use Exception;

class ColumnNameException extends Exception
{
    public function render()
    {
        $apiController = new ApiController();
        return $apiController->respondError(
            trans('messages.custom.errors.column_name'),
            400,
            1003
        );
    }
}
