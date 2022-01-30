<?php

namespace App\Contracts;

interface StorageContract
{
    public function setFactory(FactoryContract $factory): static;
}
