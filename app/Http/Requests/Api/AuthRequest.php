<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

use App\Models\Api\Admin\User;

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
    public function rules()
    {
        return [
            'email' => ['required', 'string', 'email:rfc,dns'],
            // 'phone' => ['string', 'nullable', Rule::phone()->detect(), Rule::unique((new User())->getTable(), 'phone')],
            'password'   => ['required', 'string', 'min:6'],
        ];
    }

    public function getEmail()
    {
        return (string) trim($this->input('email'));
    }
    public function getPassword()
    {
        return (string) trim($this->input('password'));
    }
}
