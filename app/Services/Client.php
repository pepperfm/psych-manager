<?php

namespace App\Services;

use App\Contracts\ClientContract;
use App\Dto\ClientDto;

class Client implements ClientContract
{

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
        // TODO: Implement loadFromDto() method.
    }
}
