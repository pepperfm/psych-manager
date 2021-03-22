<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Session
 * @package App\Models
 *
 * @property int $id
 * @property int $client_id
 * @property int $doctor_id
 * @property int $status
 * @property string $comment
 * @property \DateTime $session_date
 *
 * @property User $user
 */
class Session extends Model
{
    use HasFactory;

    protected $table = 'sessions';

    protected $fillable = ['status', 'comment', 'session_date'];

    protected $dates = ['session_date' => 'datetime:d-m-Y H:i'];

    /**
     * @param $value
     *
     * @throws \Exception
     * @return string
     */
    public function getSessionDateAttribute($value): string
    {
        return (new \DateTime($value))->format('d-m-Y H:i');
    }
    /**
     * @throws \Exception
     * @return string
     */
    public function getSessionDateSecondsAttribute(): string
    {
        return (new \DateTime($this->session_date))->format('Y-m-d H:i:s');
    }
    /**
     * @throws \Exception
     * @return string
     */
    public function getSessionDateCalendarAttribute(): string
    {
        return (new \DateTime($this->session_date))->format('Y-m-d H:i');
    }

    /**
     * -------------------------------------
     * RELATIONS
     * -------------------------------------
     */

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id', 'id');
    }
}
