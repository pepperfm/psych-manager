<?php

namespace App\Models\Api\Admin;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ConnectionType
 * @package App\Models\Api\Admin
 *
 * @property int $id
 * @property string $name
 */
class ConnectionType extends Model
{
    const CONNECTION_PHONE = 1;
    const CONNECTION_EMAIL = 2;
    const CONNECTION_VK = 3;
    const CONNECTION_VIBER = 4;
    const CONNECTION_WHATSAPP = 5;
    const CONNECTION_TELEGRAM = 6;

    protected $table = 'connection_types';

    protected $fillable = ['name'];

    /**
     * -------------------------------------
     * RELATIONS
     * -------------------------------------
     */

    /**
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'connection_type_id', 'id');
    }
}
