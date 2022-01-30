<?php

namespace App\Services\Core;

use App\Contracts\ClientContract;
use App\Dto\ClientDto;

class Client implements ClientContract
{
    public ?int $id = null;
    public ?int $user_id = null;
    public ?string $name = null;
    public ?string $email = null;
    public ?string $phone = null;
    public ?int $category_id = null;
    public ?string $birthday_date = null;
    public ?int $role = null;
    public ?int $gender = null;
    public int $connection_type_id;
    public ?string $connection_type_link = null;
    public ?string $curator_contacts = null;
    public ?bool $meeting_type = null;
    public ?int $problem_severity = null;
    public ?string $plan = null;
    public ?string $request = null;
    public ?string $notes = null;
    public ?string $concept_vision;

    public function __construct(?ClientDto $dto = null)
    {
        if ($dto) {
            $this->loadFromDto($dto);
        }
    }

    /**
     * @param ClientDto $dto
     *
     * @return $this
     */
    public function loadFromDto(ClientDto $dto): static
    {
        $this->id = $dto->id;
        $this->user_id = $dto->user_id;
        $this->name = $dto->name;
        $this->email = $dto->email;
        $this->phone = $dto->phone;
        $this->category_id = $dto->category_id;
        $this->birthday_date = $dto->birthday_date;
        $this->role = $dto->role;
        $this->gender = $dto->gender;
        $this->connection_type_id = $dto->connection_type_id;
        $this->connection_type_link = $dto->connection_type_link;
        $this->curator_contacts = $dto->curator_contacts;
        $this->meeting_type = $dto->meeting_type;
        $this->problem_severity = $dto->therapy->problem_severity;
        $this->plan = $dto->therapy->plan;
        $this->request = $dto->therapy->request;
        $this->notes = $dto->therapy->notes;
        $this->concept_vision = $dto->therapy->concept_vision;

        return $this;
    }
}
