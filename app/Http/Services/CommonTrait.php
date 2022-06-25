<?php

namespace App\Http\Services;

use Carbon\Carbon;

trait CommonTrait
{
    public static function getExpirationTime($ttl)
    {
        return Carbon::now()->addMonths($ttl)->toDateTimeString();
    }
}
