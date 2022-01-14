<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Builder, Factories\HasFactory, Relations\BelongsTo
};
use Illuminate\Support\Facades\Auth;

use Bkwld\Cloner\Cloneable;

use App\Traits\PaginationTrait;

use App\Models\Scopes\SessionUserScope;

/**
 * Class Session
 * @package App\Models
 *
 * @property int $id
 * @property int $client_id
 * @property int $doctor_id
 * @property string $comment
 * @property \DateTime $session_date
 *
 * @property User $user
 */
class Session extends BaseModel
{
    use HasFactory, Cloneable, PaginationTrait;

    /**
     * @inheritdoc
     */
    protected $table = 'sessions';

    /**
     * @inheritdoc
     */
    protected $fillable = ['comment', 'session_date'];

    /**
     * @inheritdoc
     */
    protected $dates = ['session_date' => 'datetime:d-m-Y H:i'];

    /** @var array|string[] $cloneable_relations */
    protected array $cloneable_relations = ['user'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new SessionUserScope(Auth::id() ?? 1));
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
        $connectionType = !empty($filters->fields->connection_type) || $filters->fields->connection_type === 0;

        $query
            ->when(!empty($filters->fields->date_range), function ($q) use ($filters) {
                return $q->whereBetween('session_date', [$filters->fields->date_range[0], $filters->fields->date_range[1]]);
            })
            ->when($meetingType, function ($q) use ($filters) {
                return $q->whereHas('user', function ($qq) use ($filters) {
                    return $qq->where('meeting_type', $filters->fields->meeting_type);
                });
            })
            ->when($connectionType, function ($q) use ($filters) {
                return $q->whereHas('user', function ($qq) use ($filters) {
                    return $qq->whereHas('connectionType', function ($qqq) use ($filters) {
                        return $qqq->where('id', $filters->fields->connection_type);
                    });
                });
            })
            ->when(!empty($filters->fields->user_name), function ($q) use ($filters) {
                return $q->whereHas('user', function ($qq) use ($filters) {
                    return $qq->where('name', 'like', "%{$filters->fields->user_name}%");
                });
            });

        return $query;
    }

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
     * @param string $comment
     *
     * @return $this
     */
    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @param string $sessionDate
     *
     * @return $this
     */
    public function setSessionDate(string $sessionDate): static
    {
        $this->setAttribute('session_date', $sessionDate);

        return $this;
    }

    /**
     * -------------------------------------
     * RELATIONS
     * -------------------------------------
     */

    /**
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
