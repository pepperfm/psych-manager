<?php

namespace App\Http\Requests\Api;

/**
 * Class AuthRequest
 * @package App\Http\Requests\Api
 *
 * @OA\Schema(schema="AuthRequest",
 *     @OA\Property(property="email", type="string", description="User email", example="admin@yandex.ru"),
 *     @OA\Property(property="password", type="string", description="User password", example="123456"),
 * )
 */
class AuthRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email:rfc,dns'],
            // 'phone' => ['string', 'nullable', Rule::phone()->detect(), Rule::unique((new User())->getTable(), 'phone')],
            'password'   => ['required', 'string', 'min:6'],
        ];
    }

    public function getEmail(): string
    {
        return trim($this->input('email'));
    }
    public function getPassword(): ?string
    {
        return trim($this->input('password'));
    }
}
