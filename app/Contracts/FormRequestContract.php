<?php

namespace App\Contracts;

use App\Dto\ClientDto;

interface FormRequestContract
{
    public function toDto(): ClientDto;
}
