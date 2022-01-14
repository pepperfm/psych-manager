<?php

namespace App\Http\Resources\Api\Client;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

use App\Models\User;

/**
 * Class IndexResource
 * @package App\Http\Resources\Api\Client
 * @mixin User
 *
 * @OA\Schema(schema="ClientIndexResource",
 *     type="object",
 *     @OA\Property(property="id", type="string"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="email", type="string"),
 *     @OA\Property(property="connection_type", type="integer", description="id записи типа связи"),
 *     @OA\Property(property="connection_type_string", type="string", description="Имя записи типа связи", example="ВКонтакте"),
 * )
 */
class IndexResource extends JsonResource
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
            'category' => $this->whenLoaded('category') ? $this->category->name : '',
            'connection_type_string' => $this->connectionType->name,
            'meeting_type_icon' => $this->meeting_type ? 'el-icon-office-building' : 'el-icon-service',
            'session_id' => $this->latestSession->first()->id ?? '',
            'session_date' => $this->latestSession->first()->session_date ?? '',
            'deleted' => !is_null($this->deleted_at),
            'name_label' => !is_null($this->deleted_at) ? 'info' : 'primary'
        ];
    }
}
