<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, ModelNotFoundException};

class Interpreter extends Model
{
    protected $fillable = [
        'identifier',
        'description',
        'html',
        'api_prefix'
    ];
    protected $connection = 'pgsql';
    protected $table = 'interpreters';

    public static function getBy($by, $value)
    {
        $items = self::where($by, 'like', $value)->get();
        if ($items->count() > 0) {
          return $items->toArray();
        } else {
            throw new ModelNotFoundException();
        }
    }

    public static function show($id)
    {
        $item = self::where('id', $id)->firstOrFail();
        return $item->toArray();
    }

    public static function store($data)
    {

        return self::create($data);
    }

    public static function remove($id)
    {
        $item = self::where('id', $id)->firstOrFail();
        $item->delete();
        return $item;
    }

    public static function updateItem($id, $list)
    {

        $item = self::findOrFail($id);
        foreach ($list as $key => $value) {
            $item->update([$key => $value]);
        }

    }

}
