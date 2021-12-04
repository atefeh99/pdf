<?php
namespace App\Models;

use App\Helpers\Date;

trait Common
{

    public function getCreatedAtAttribute($value)
    {
        return Date::convertCarbonToJalali($value);
    }
    public function getUpdatedAtAttribute($value)
    {
        return Date::convertCarbonToJalali($value);
    }
    public function getBarcodesAttribute($value)
    {
        return json_decode($value, true);
    }
    public function setBarcodesAttribute($value)
    {
        $this->attributes['barcodes'] = json_encode($value);
    }
    public function getExpiredAtAttribute($value)
    {
        return Date::convertCarbonToJalali($value);
    }

}
