<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Builder,
    Casts\Attribute,
    Factories\HasFactory,
    Relations\BelongsTo
};
use Illuminate\Database\Query\Builder as QueryBuilder;

use Illuminate\Support\Facades\Auth;

use Bkwld\Cloner\Cloneable;

use App\Builders\FilterBuilder;

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
    use HasFactory, Cloneable;

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
     * @param QueryBuilder $query
     *
     * @return FilterBuilder|QueryBuilder|Builder
     */
    public function newEloquentBuilder($query): FilterBuilder|QueryBuilder|Builder
    {
        return new FilterBuilder($query);
    }

    /**
     * @throws \Exception
     * @return Attribute
     */
    public function sessionDate(): Attribute
    {
        return new Attribute(
            get: fn($value) => (new \DateTime($value))->format('d-m-Y H:i')
        );
    }

    /**
     * @throws \Exception
     * @return Attribute
     */
    public function sessionDateSeconds(): Attribute
    {
        return new Attribute(
            get: fn($value) => (new \DateTime($this->session_date))->format('Y-m-d H:i:s')
        );
    }

    /**
     * @throws \Exception
     * @return Attribute
     */
    public function sessionDateCalendar(): Attribute
    {
        return new Attribute(
            get: fn($value) => (new \DateTime($this->session_date))->format('Y-m-d H:i')
        );
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
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
