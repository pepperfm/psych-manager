<?php

namespace App\Http\Resources\Api\Client;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

use App\Models\ClientTherapy;

/** @mixin ClientTherapy */
class ClientTherapyResource extends JsonResource
{
    /**
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'request' => $this->request,
            'problem_severity' => $this->problem_severity,
            'plan' => $this->plan,
            'notes' => $this->notes,
            'concept_vision' => $this->concept_vision,
            'client_id' => $this->client_id,
            'client' => new IndexResource($this->whenLoaded('client')),
        ];
    }
}
