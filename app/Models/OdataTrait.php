<?php


namespace App\Models;

use App\Exceptions\ColumnNameException;
use App\Helpers\Odata\OdataFilterOperator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use function PHPUnit\Framework\stringStartsWith;

trait OdataTrait
{


    private static $columns;

    public static function getItems($data)
    {
        $builder = self::query();
        $tableName = $builder->getModel()->getTable();
        static::$columns = Schema::getColumnListing($tableName);
        foreach ($data as $Key => $values) {
            if (is_array($values)) {
                foreach ($values as $item) {
                    if ($Key == 'filter') {
                        $builder = self::{$Key}($builder, $item["left"], $item["operator"], $item["right"]);
                    } elseif ($Key == 'orderBy') {
                        $builder = self::{$Key}($builder, $item['property'], $item['direction']);
                    }
                }
            }
        }
        $builder->skip($data['skip'])
            ->take($data['top']);
        return $builder->get();
    }

    /**
     * @param Builder $builder
     * @param $column
     * @param $operator
     * @param $value
     * @return Builder
     * @throws ColumnNameException
     */
    private static function filter(Builder $builder, $column, $operator, $value): Builder
    {
        if($column == 'barcode'){
            $column='barcodes';
        }
        static::checkColumn($column);
        if ($operator == OdataFilterOperator::IN) {
            return $builder->whereIn($column, $value);
        }elseif($column == 'barcodes'){
            return $builder->whereRaw("barcodes @> '[\"$value\"]'");
        }
        return $builder->where($column, $operator, $value);

    }

    /**
     * @param Builder $builder
     * @param $property
     * @param $direction
     * @return Builder
     * @throws ColumnNameException
     */
    private static function orderBy(Builder $builder, $property, $direction): Builder
    {
        static::checkColumn($property);
        return $builder->orderBy($property, $direction);
    }

    /**
     * @throws ColumnNameException
     */
    private static function checkColumn($column)
    {
        if (!in_array($column, self::$columns)) {
            throw new ColumnNameException();
        }
    }
}


