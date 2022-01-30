<?php

namespace App\Http\Resources\Api\Client;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

use App\Http\Resources\Api\Sessions\IndexResource;

use App\Models\Client;

/**
 * Class ShowResource
 * @package App\Http\Resources\Api\Client
 * @mixin Client
 *
 * @OA\Schema(
 *     schema="ClientShowResource",
 *     allOf={
 *         @OA\Schema(
 *             type="object",
 *             @OA\Property(property="id", type="string"),
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="email", type="string"),
 *             @OA\Property(property="connection_type", type="integer", description="id записи типа связи"),
 *             @OA\Property(property="gender", type="integer", description="пол"),
 *         ),
 *         @OA\Schema(ref="#/components/schemas/SessionIndexResource"),
 *     }
 * )
 */
class ShowResource extends JsonResource
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
            'email' => $this->email,
            'name' => $this->name,
            'phone' => $this->phone,
            'birthday_date' => $this->birthday_date,
            'session_id' => $this->latestSession->id ?? '',
            'category_id' => $this->category_id,
            'category' => $this->category->name ?? '',
            'connection_type_id' => $this->connection_type_id,
            'curator_contacts' => $this->curator_contacts,
            'meeting_type_icon' => $this->meeting_type ? 'el-icon-office-building' : 'el-icon-service',
            'connection_type_string' => $this->connectionType->name,
            'connection_type_link' => $this->connection_type_link,
            'meeting_type' => $this->meeting_type,
            'gender' => $this->gender,

            'therapy' => ClientTherapyResource::make($this->whenLoaded('therapy')),
            'sessions' => IndexResource::collection($this->whenLoaded('sessions')),
        ];
    }
}
