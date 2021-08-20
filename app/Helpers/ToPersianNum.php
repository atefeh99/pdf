<?php
namespace App\Helpers;

function convertToPersianNumber($value){
    $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $num = range(0, 9);
    return str_replace($num, $persian, $value);
}

