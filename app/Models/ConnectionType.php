<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class ConnectionType
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 */
class ConnectionType extends BaseModel
{
    /**
     * @inheritdoc
     */
    protected $table = 'connection_types';

    /**
     * @inheritdoc
     */
    protected $fillable = ['name'];

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * -------------------------------------
     * RELATIONS
     * -------------------------------------
     */

    /**
     * @return HasMany
     */
    public function clients(): HasMany
    {
        return $this->hasMany(User::class, 'connection_type_id', 'id');
    }
}
