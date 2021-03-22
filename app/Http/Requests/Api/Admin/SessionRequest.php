<?php

namespace App\Http\Requests\Api\Admin;

/**
 * Class SessionRequest
 * @package App\Http\Requests\Api\Admin
 *
 * @OA\Schema(schema="SessionRequest",
 *     @OA\Property(property="client_id", type="integer"),
 *     @OA\Property(property="doctor_id", type="integer"),
 *     @OA\Property(property="status", type="string"),
 *     @OA\Property(property="session_date", type="string", description="date_format Y-m-d H:i:s"),
 *     @OA\Property(property="comment", type="string", description="План на сессию"),
 * )
 */
class SessionRequest extends BaseAdminRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer'],
            'doctor_id' => ['sometimes', 'integer'],
            'session_date' => ['required', 'date_format:"Y-m-d H:i:s"'],
            'comment' => ['sometimes', 'nullable', 'string', 'max:65534']
        ];
    }

    public function getClientId(): int
    {
        return (int) $this->input('user_id');
    }
}
