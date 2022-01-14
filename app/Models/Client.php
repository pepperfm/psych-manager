<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\{
    BelongsTo, BelongsToMany, HasMany, HasOne
};
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
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
