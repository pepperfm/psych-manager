<?php

namespace App\Http\Resources\Admin\UserTherapy;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

use App\Http\Resources\Admin\User\IndexResource;

use App\Models\Api\Admin\UserTherapy;

/** @mixin UserTherapy */
class UserTherapyResource extends JsonResource
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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user_id' => $this->user_id,
            'users' => new IndexResource($this->whenLoaded('users')),
        ];
    }
}
