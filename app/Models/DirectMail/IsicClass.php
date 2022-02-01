<?php


namespace App\Models\DirectMail;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class IsicClass extends Model
{
    protected $connection = 'sina';
    protected $table = 'isic_class';
    protected static $_table = 'isic_class';

    public static function getName($id)
    {
        $name = self::findOrFail($id)->name;
        if (empty($name)) {
            throw new ModelNotFoundException();
        }
        return $name;
    }
}
