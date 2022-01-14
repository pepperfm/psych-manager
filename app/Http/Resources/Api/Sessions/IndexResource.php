<?php

namespace App\Http\Resources\Api\Sessions;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

use App\Http\Resources\Api\Client\ShowResource;

use App\Models\Session;

/**
 * Class IndexResource
 * @package App\Http\Resources\Api\Sessions
 * @mixin Session
 *
 * @OA\Schema(schema="SessionIndexResource",
 *     type="object",
 *     @OA\Property(property="id", type="string"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="email", type="string"),
 *     @OA\Property(property="connection_type", type="integer", description="id записи типа связи"),
 *     @OA\Property(property="connection_type_string", type="string", description="Имя записи типа связи"),
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
            'client_id' => $this->client_id,
            'user_id' => $this->user_id,
            'comment' => $this->comment,
            'session_date' => $this->session_date,
            'client' => ShowResource::make($this->client),
        ];
    }
}
