<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\{
    BelongsToMany, HasMany
};

/**
 * Class Category
 * @package App\Models
 *
 * @property string $name
 */
class Category extends BaseModel
{
    /**
     * @inheritdoc
     */
    protected $table = 'categories';

    /**
     * @inheritdoc
     */
    protected $fillable = ['name'];

    /**
     * @inheritdoc
     */
    public $timestamps = false;

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
     * @return HasMany
     */
    public function clients(): HasMany
    {
        return $this->hasMany(User::class, 'category_id', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(Client::class);
    }
}
