<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\PostalcodesService;
use App\Exceptions\UnauthorizedUserException;

class PostalcodesController
{
    use RulesTrait;
    public function getItems(Request $request, $plate_id)
    {
        $user_id = $request->header('x-user-id');
        if (!isset($user_id)) {
            throw new UnauthorizedUserException(trans('messages.custom.unauthorized_user'), 1001);
        }

        $data = self::checkRules(
            ['plate_id' => $plate_id],
            __FUNCTION__,
            4000,
        );
    
        PostalcodesService::getItems($data, $user_id);
    }
}