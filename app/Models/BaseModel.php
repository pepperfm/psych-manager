<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Model
 *
 * @package App\Models
 */
abstract class BaseModel extends Model
{
    /**
     * @return Builder
     */
    public function queryModel(): Builder
    {
        return $this->newQuery();
    }

    /**
     * @return Builder
     */
    public static function q(): Builder
    {
        return static::query();
    }

    /**
     * @return static
     */
    public static function n(): self
    {
        return (new static());
    }
}
