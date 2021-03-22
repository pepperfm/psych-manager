<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

use Laravel\Passport\HasApiTokens;

use App\Helpers\PhoneHelper;

use App\Models\Api\Admin\ConnectionType;
use App\Models\Api\Admin\Session;
use App\Models\Api\Admin\UserTherapy;
use App\Models\Api\Admin\Category;
use App\Models\Api\Admin\Doctor;

/**
 * Class User
 * @package App\Models
 *
 * @property int $id
 * @property string $email
 * @property string $name
 * @property string $password
 * @property string $auth_token
 * @property \DateTime $auth_token_expire
 *
 * @property Session $sessions
 * @property ConnectionType $connectionType
 *
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    const ROLE_CLIENT = 0;
    const ROLE_DOCTOR = 1;
    const ROLE_VISITOR = 5;
    const ROLE_ULTRA_ADMIN = 10;

    const MEETING_TYPE_ONLINE = 0;
    const MEETING_TYPE_OFFLINE = 1;

    const GENDER_FEMALE = 0;
    const GENDER_MALE = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'birthday_date',
        'gender',
        'role',
        'meeting_type',
        'connection_type',
        'connection_type_link',
        'curator_contacts'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @param string $email
     *
     * @return Builder|Model|object|null
     */
    public static function findByEmail(string $email)
    {
        return self::where('email', $email)->first();
    }

    /**
     * @param string $email
     * @param string $password
     *
     * @return self
     */
    public static function createNew(string $email, string $password): User
    {
        $user = new self;
        $user->name = 'Не задано';
        $user->email = $email;
        $user->password = $password;
        $user->save();

        return $user;
    }

    /**
     * Always encrypt password when it is updated.
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make((string) $value, [PASSWORD_DEFAULT]);
    }

    /**
     * @param $value
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = PhoneHelper::clear($value);
    }

    /**
     * -------------------------------------
     * RELATIONS
     * -------------------------------------
     */

    /**
     * @return BelongsTo
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function latestSession(): HasMany
    {
        return $this->hasMany(Session::class, 'user_id', 'id')->latest('session_date');
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
        return $this->hasOne(UserTherapy::class, 'user_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
