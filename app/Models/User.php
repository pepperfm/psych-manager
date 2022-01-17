<?php

namespace App\Models;

use App\Builders\FilterBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\{
    Builder,
    Casts\Attribute,
    Relations\BelongsToMany,
    SoftDeletes
};
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\{
    Collection,
    Facades\Auth, Facades\Hash
};

use Laravel\Passport\HasApiTokens;

use App\Models\Scopes\SessionUserScope;
use App\Helpers\PhoneHelper;
use App\Traits\PaginationTrait;

/**
 * Class User
 * @package App\Models
 *
 * @property int $id
 * @property string $email
 * @property string $name
 * @property string $password
 *
 * Relation:
 * @property Session[]|Collection $sessions
 * @property ConnectionType[]|Collection $connectionType
 *
 * Scopes:
 * @method Builder withFilters()
 * @method Builder paginationApi()
 * @method Builder withTrashed()
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    /**
     * @inheritdoc
     */
    protected $table = 'users';

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'gender',
    ];

    /**
     * @inheritdoc
     */
    protected $hidden = [
        'password',
    ];

    /**
     * @inheritdoc
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @inheritdoc
     */
    protected static function booted(): void
    {
        // static::addGlobalScope(new SessionUserScope(Auth::id() ?? 1));
    }

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
     * Always encrypt password when it is updated.
     *
     * @return Attribute
     */
    public function password(): Attribute
    {
        return new Attribute(
            set: fn($value) => Hash::make((string) $value, [PASSWORD_DEFAULT])
        );
    }

    /**
     * @return Attribute
     */
    public function phone(): Attribute
    {
        return new Attribute(
            set: fn($value) => PhoneHelper::clear($value)
        );
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
        return $this->hasMany(Client::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class, 'client_id', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
