<?php

namespace App\Http\Requests\Api;

use App\Contracts\FormRequestContract;

use App\Dto\ClientDto;
use App\Dto\TherapyDto;
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
            'role' => ['sometimes', 'nullable', 'integer'],
            'gender' => ['sometimes', 'nullable', 'boolean'],
            'connection_type_id' => ['required', 'integer'],
            'connection_type_link' => $this->connectionTypeLink,
            'curator_contacts' => ['sometimes', 'string', 'nullable', 'max:255'],
            'meeting_type' => ['sometimes', 'nullable', 'boolean'],
            'therapy.problem_severity' => ['sometimes', 'nullable', 'integer'],
            'therapy.plan' => ['sometimes', 'nullable', 'string'],
            'therapy.request' => ['sometimes', 'nullable', 'string'],
            'therapy.notes' => ['sometimes', 'nullable', 'string'],
            'therapy.concept_vision' => ['sometimes', 'nullable', 'string'],
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
        $dto = new ClientDto();
        $dto->id = $this->route('client');
        $dto->user_id = \Auth::id();
        $dto->name = $this->input('name');
        $dto->email = $this->input('email');
        $dto->phone = $this->input('phone');
        $dto->category_id = $this->input('category_id');
        $dto->birthday_date = $this->input('birthday_date');
        $dto->role = $this->input('role');
        $dto->gender = $this->input('gender');
        $dto->connection_type_id = $this->input('connection_type_id');
        $dto->connection_type_link = $this->input('connection_type_link');
        $dto->curator_contacts = $this->input('curator_contacts');
        $dto->meeting_type = $this->input('meeting_type');

        $dto->therapy = new TherapyDto($this->input('therapy'));

        return $dto;
    }
}
