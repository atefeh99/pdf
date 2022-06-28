<?php
/**
 * Created by PhpStorm.
 * User: zrhm7232
 * Date: 2/27/19
 * Time: 11:15 AM
 */

namespace App\Database\Entity;

use Illuminate\Database\Eloquent\Model;

class SuccessJobs extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'success_jobs';

    public static function createItem($data)
    {
       
        $item = self::create($data);
        return $item;
    }
    public function setDataAttribute($value)
    {
        $this->attributes['data'] = json_encode($value);
    }

    public function getDataAttribute($value)
    {
        return json_decode($value, true);
    }

}
