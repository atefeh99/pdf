<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{Model, ModelNotFoundException};

class Interpreter extends Model
{
    protected $fillable = [
        'identifier',
        'description',
        'html'
    ];
    protected $connection = 'pgsql1';
    protected $table = 'interpreters';

    public static function index()
    {
        $items = self::all()->toArray();
        if (empty($items)) throw new ModelNotFoundException();
        return $items;
    }

    public static function show($id)
    {
        $item = self::where('id',$id)->firstOrFail();
        return $item->toArray();
    }

//    public static function getHtml($id)
//    {
//
//        $item = self::findOrFail($id);
//        return ['html' => $item->html, 'identifier' => $item->identifier];
//    }

    public static function store($data)
    {
        return self::create($data);
    }

    public static function remove($id)
    {
        $item = self::where('id',$id)->firstOrFail();
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
