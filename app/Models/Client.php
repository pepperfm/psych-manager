<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\{
    BelongsTo, HasMany, HasOne
};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Query\Builder as QueryBuilder;

use App\Builders\FilterBuilder;

use App\Models\Scopes\SessionUserScope;

class Client extends User
{
    use HasFactory;

    /**
     * @inheritdoc
     */
    protected $table = 'clients';

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'birthday_date', 'gender',
        'role', 'meeting_type', 'connection_type', 'connection_type_link', 'curator_contacts',
    ];

    /**
     * @param QueryBuilder $query
     *
     * @return FilterBuilder|QueryBuilder|Builder
     */
    public function newEloquentBuilder($query): FilterBuilder|QueryBuilder|Builder
    {
        return new FilterBuilder($query);
    }

    /**
     * @inheritdoc
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new SessionUserScope(\Auth::id() ?? 1));
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class, 'client_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function latestSession(): HasMany
    {
        return $this->hasMany(Session::class, 'client_id', 'id')->latest('session_date');
    }

    /**
     * @return BelongsTo
     */
    public function connectionType(): BelongsTo
    {
        return $this->belongsTo(ConnectionType::class, 'connection_type_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function therapy(): HasOne
    {
        return $this->hasOne(ClientTherapy::class, 'client_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
