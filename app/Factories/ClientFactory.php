<?php

namespace App\Factories;

use App\Contracts\{ClientContract, FactoryContract, FormRequestContract};
use App\Services\Core\Client;
use App\Dto\ClientDto;

class ClientFactory implements FactoryContract
{
    protected ClientDto $dto;

    public function __construct()
    {
        $this->dto = new ClientDto();
    }

    /**
     * @param FormRequestContract $request
     *
     * @return $this
     */
    public function fromRequest(FormRequestContract $request): static
    {
        $this->dto = $request->toDto();

        return $this;
    }

    /**
     * @return ClientContract
     */
    public function make(): ClientContract
    {
        return new Client($this->dto);
    }
}
