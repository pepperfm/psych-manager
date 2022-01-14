<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Database\Factories\ClientTherapyFactory;

class ClientTherapy extends BaseModel
{
    use HasFactory;

    /**
     * @inheritdoc
     */
    protected $table = 'client_therapies';

    /**
     * @inheritdoc
     */
    protected $fillable = ['problem_severity', 'plan', 'request', 'concept_vision', 'notes'];

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return ClientTherapyFactory::new();
    }

    /**
     * @param int|null $problemSeverity
     *
     * @return $this
     */
    public function setProblemSeverity(?int $problemSeverity): static
    {
        $this->problem_severity = $problemSeverity;

        return $this;
    }

    /**
     * @param string|null $plan
     *
     * @return $this
     */
    public function setPlan(?string $plan): static
    {
        $this->plan = $plan;

        return $this;
    }

    /**
     * @param string|null $request
     *
     * @return $this
     */
    public function setRequest(?string $request): static
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @param string|null $notes
     *
     * @return $this
     */
    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * @param string|null $conceptVision
     *
     * @return $this
     */
    public function setConceptVision(?string $conceptVision): static
    {
        $this->concept_vision = $conceptVision;

        return $this;
    }

    /**
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
