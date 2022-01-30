<?php

namespace App\Services;

use Illuminate\Support\Collection;

use App\Builders\FilterBuilder;

use App\Services\Storages\ClientDbStorage;
use App\Factories\ClientFactory;

use App\Contracts\{FactoryContract, StorageContract, ClientContract};

use App\Models\{Client, User};

class ClientService
{
    protected FactoryContract $factory;
    protected ClientContract $client;
    protected StorageContract $storage;

    public function __construct()
    {
        $this->factory = new ClientFactory();
        $this->storage = new ClientDbStorage();
        $this->storage->setFactory($this->factory);
    }

    /**
     * @return FactoryContract
     */
    public function getFactory(): FactoryContract
    {
        return $this->factory;
    }

    public function setClient(ClientContract $client): static
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @param ClientContract $client
     *
     * @return $this
     */
    public function saveClient(ClientContract $client): static
    {
        $this->setClient($client);
        $this->save();

        return $this;
    }

    /**
     * @return Client
     */
    public function save(): Client
    {
        return $this->storage->save($this->client);
    }

    /**
     * @param array $filters
     * @param ?int $total
     * @param User $user
     *
     * @return Collection
     */
    public function getUsersWithFilters(array $filters, int|null &$total, User $user): Collection
    {
        /** @var FilterBuilder $clientsQ */
        $clientsQ = $user->clients()
            ->with(['sessions', 'connectionType', 'therapy', 'category'])
            ->clientFilters($filters['fields'] ?? []);
        $total = $clientsQ->count();

        return $clientsQ->withPagination($filters['pagination'] ?? [])
            ->sort($filters['sort'] ?? [])
            ->withTrashed()
            ->oldest('deleted_at')
            ->get();
    }
}
