<?php

namespace App\Models\Api\Admin;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Database\Factories\UserTherapyFactory;

class UserTherapy extends Model
{
    use HasFactory;

    protected $table = 'user_therapies';
    protected $fillable = ['problem_severity', 'plan', 'request', 'concept_vision', 'notes'];

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return UserTherapyFactory::new();
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
