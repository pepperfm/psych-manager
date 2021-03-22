<?php

namespace App\Models\Api\Admin;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\User as BaseUser;

class Doctor extends BaseUser
{
    protected $table = 'doctors';
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'gender',
    ];

    public function regenerateAuthToken()
    {
        $this->auth_token = md5(time() . \uniqid('auth_key'));
        $this->auth_token_expire = (new \DateTime)->add(new \DateInterval('P7D'))->format('Y-m-d H:i:s');
        $this->save();
    }

    /**
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(parent::class, 'doctor_id', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
