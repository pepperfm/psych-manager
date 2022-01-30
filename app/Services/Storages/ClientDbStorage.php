<?php

namespace App\Services\Storages;

use App\Factories\ClientFactory;

use App\Contracts\{ClientContract, FactoryContract, StorageContract};

use App\Models\{
    Client as ClientModel, Category, ClientTherapy, ConnectionType
};

class ClientDbStorage implements StorageContract
{
    protected FactoryContract $factory;

    public function __construct()
    {
        $this->factory = new ClientFactory();
    }

    /**
     * @param FactoryContract $factory
     *
     * @return $this
     */
    public function setFactory(FactoryContract $factory): static
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * @param ClientContract $client
     *
     * @return ClientModel
     */
    public function save(ClientContract $client): ClientModel
    {
        if ($client->id) {
            $newModel = ClientModel::query()->find($client->id);
        } else {
            $newModel = new ClientModel((array) $client);
        }

        $connectionType = ConnectionType::find($client->connection_type_id);
        $category = Category::find($client->category_id);

        $newModel->user()->associate($client->user_id);
        $newModel->category()->associate($category);

        if (!$connectionType->clients()->save($newModel)) {
            throw new \RuntimeException('Ошибка сохранения клиента', 500);
        }

        $therapy = ClientTherapy::n()
            ->setProblemSeverity($client->problem_severity)
            ->setPlan($client->plan)
            ->setRequest($client->request)
            ->setNotes($client->notes)
            ->setConceptVision($client->concept_vision);

        $therapy->client()->associate($newModel);

        if (!$therapy->save()) {
            throw new \RuntimeException('Ошибка создания данных терапии', 500);
        }

        return $newModel;
    }
}
