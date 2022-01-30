<?php

namespace App\Dto;

class ClientDto extends BaseDto
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
    public TherapyDto $therapy;
}
