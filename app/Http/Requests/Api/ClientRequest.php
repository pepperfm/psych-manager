<?php

namespace App\Http\Requests\Api;

use App\Contracts\FormRequestContract;

use App\Dto\ClientDto;
use App\Enums\ConnectionTypeEnum;

/**
 * Class ClientRequest
 * @package App\Http\Requests\Api
 *
 * @OA\Schema(schema="ClientRequest",
 *     @OA\Property(property="name", type="string", description="Имя"),
 *     @OA\Property(property="email", type="string", example="someuser@gmail.com", description="Email"),
 *     @OA\Property(property="phone", type="string", example="88005553535", description="Телефон"),
 *     @OA\Property(property="role", type="integer", description="Роль: клиент или специалист"),
 *     @OA\Property(property="gender", type="integer", description="Пол"),
 *     @OA\Property(property="connection_type", type="integer", example=1, description="Id предпочитаемого способа связи"),
 * )
 */
class ClientRequest extends BaseApiRequest implements FormRequestContract
{
    public function rules(): array
    {
        $this->sanitize();

        if ($this->isMethod('POST')) {
            $this->email = ['sometimes', 'nullable', 'email:rfc,dns', 'max:255', 'unique:users,email'];
            $this->phone = ['sometimes', 'nullable', 'string', 'max:18', 'unique:users,phone'];
        }

        return [
            'name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'email' => $this->email,
            'phone' => $this->phone,
            'category_id' => ['sometimes', 'integer', 'exists:categories,id'],
            'birthday_date' => ['sometimes', !$this->isEmptyString('birthday_date') ? 'date_format:"Y-m-d"' : ''],
            'password' => ['sometimes', 'nullable', 'string', 'min:6'],
            'role' => ['sometimes', 'nullable', 'integer'],
            'gender' => ['sometimes', 'nullable', 'boolean'],
            'connection_type_id' => ['required', 'integer'],
            'connection_type_link' => $this->connectionTypeLink,
            'curator_contacts' => ['sometimes', 'string', 'nullable', 'max:255'],
            'meeting_type' => ['sometimes', 'nullable', 'boolean'],
            'therapy.problem_severity' => ['sometimes', 'nullable', 'integer'],
            'therapy.plan' => ['sometimes', 'nullable', 'string'],
            'therapy.request' => ['sometimes', 'nullable', 'string'],
            'notes' => ['sometimes', 'nullable', 'string'],
            'concept_vision' => ['sometimes', 'nullable', 'string'],
        ];
    }

    public function getConnectionType(): int
    {
        return $this->input('connection_type_id', ConnectionTypeEnum::PHONE->value);
    }

    public function getCategoryId(): int
    {
        return $this->input('category_id');
    }

    public function toDto(): ClientDto
    {
        return new ClientDto($this->validated());
    }
}
