<?php

namespace App\Http\Resources\Admin\Sessions;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

use App\Models\Api\Admin\Session;

/**
 * Class ShowResource
 * @package App\Http\Resources\Admin\Sessions
 * @mixin Session
 *
 * @OA\Schema(schema="SessionShowResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="client_id", type="integer"),
 *     @OA\Property(property="client_name", type="string"),
 *     @OA\Property(property="doctor_id", type="integer"),
 *     @OA\Property(property="status", type="string"),
 *     @OA\Property(property="comment", type="string", description="Комментарий/план на сессию"),
 *     @OA\Property(property="session_date", type="string", description="Дата встречи"),
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
            'user_id' => $this->user_id,
            'user_name' => $this->user->name,
            'doctor_id' => $this->doctor_id,
            'status' => $this->status,
            'comment' => $this->comment,
            'session_date' => $this->session_date_seconds,
        ];
    }
}
