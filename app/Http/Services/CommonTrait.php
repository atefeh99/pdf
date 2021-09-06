<?php

namespace App\Http\Services;

use Carbon\Carbon;

trait CommonTrait
{
    public static function getExpirationTime($ttl)
    {
        return Carbon::now()->addDays($ttl)->format('Y-m-d h:m:s');
    }
}
