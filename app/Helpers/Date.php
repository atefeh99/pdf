<?php

namespace App\Helpers;

use Carbon\Carbon;
use Morilog\Jalali\Jalalian;

class Date
{
    /**
     * Converts Carbon time to Jalali time
     *
     * @param string|null $value
     *
     * @return string|null
     */
    public static function convertCarbonToJalali(?string $value): ?string
    {
        return $value ? Jalalian::fromCarbon(Carbon::createFromTimeString($value))->toString() : $value;
    }

    /**
     * Converts Jalali time to Carbon time
     *
     * @param string|null $value
     *
     * @return string|null
     */
    public static function convertJalaliToCarbon(?string $value): ?string
    {
        return $value ? Jalalian::fromFormat('Y-m-d', $value)->toCarbon() : $value;
    }
}
