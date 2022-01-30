<?php

namespace App\Dto;

class TherapyDto extends BaseDto
{
    public ?int $problem_severity = null;
    public ?string $plan = null;
    public ?string $request = null;
    public ?string $notes = null;
    public ?string $concept_vision;
}
