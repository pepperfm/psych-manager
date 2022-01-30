<?php

namespace App\Contracts;

use App\Dto\ClientDto;

interface ClientContract
{
    public function loadFromDto(ClientDto $dto): static;
}
