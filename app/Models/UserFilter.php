<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserFilter extends BaseModel
{
    /**
     * @inheritdoc
     */
    protected $casts = [
        'value' => 'json'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
