<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\{Builder, Casts\Attribute, SoftDeletes};
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
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens, PaginationTrait;

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
     * @param Builder $query
     * @param $filters
     *
     * @return Builder
     */
    public function scopeWithFilters(Builder $query, $filters): Builder
    {
        $meetingType = !empty($filters->fields->meeting_type) || $filters->fields->meeting_type === 0;
        $categoryId = !empty($filters->fields->category_id) || $filters->fields->category_id === 0;
        $connectionType = !empty($filters->fields->connection_type) || $filters->fields->connection_type === 0;

        $query
            ->when($meetingType, function ($q) use ($filters) {
                return $q->where('meeting_type', $filters->fields->meeting_type);
            })
            ->when($categoryId, function ($q) use ($filters) {
                return $q->where('category_id', $filters->fields->category_id);
            })
            ->when(!empty($filters->fields->name), function ($q) use ($filters) {
                return $q->where('name', 'like', "%{$filters->fields->name}%");
            })
            ->when(!empty($filters->fields->email), function ($q) use ($filters) {
                return $q->where('email', 'like', "%{$filters->fields->email}%");
            })
            ->when($connectionType, function ($q) use ($filters) {
                return $q->whereHas('connectionType', function ($qq) use ($filters) {
                    return $qq->where('id', $filters->fields->connection_type);
                });
            })
            ->when(!empty($filters->fields->phone), function ($q) use ($filters) {
                return $q->where('phone', 'like', "%{$filters->fields->phone}%");
            })
            ->when(!empty($filters->fields->date_range), function ($q) use ($filters) {
                $q->whereHas('sessions', function ($qq) use ($filters) {
                    return $qq->whereBetween('session_date', [$filters->fields->date_range[0], $filters->fields->date_range[1]]);
                });
            });

        return $query;
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
}
