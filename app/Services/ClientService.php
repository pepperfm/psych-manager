<?php

namespace App\Services;

use App\Contracts\ClientContract;
use App\Factories\ClientFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

use App\Builders\FilterBuilder;

use App\Http\Requests\Api\ClientRequest;

use App\Contracts\FactoryContract;

use App\Models\{Client, User, Category, ClientTherapy, ConnectionType};

class ClientService
{
    protected FactoryContract $factory;
    protected ClientContract $client;

    public function __construct()
    {
        $this->factory = new ClientFactory();
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
        $clientsQ = $user->clients()->with(['sessions', 'connectionType', 'therapy', 'category'])
            ->clientFilters($filters['fields'] ?? []);
        $total = $clientsQ->count();

        return $clientsQ->withPagination($filters['pagination'] ?? [])
            ->withTrashed()
            ->oldest('deleted_at')
            ->get();
    }

    /**
     * @param ClientRequest $request
     * @param User $user
     *
     * @return void
     */
    public function save(ClientRequest $request, User $user): void
    {
        $client = new Client($request->validated());
        $connectionType = ConnectionType::find($request->getConnectionType());
        $category = Category::find($request->getCategoryId());

        $client->user()->associate($user);
        $client->category()->associate($category);
        $connectionType->clients()->save($client);

        if (!$client->save()) {
            throw new \RuntimeException('Ошибка сохранения клиента', JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        $therapy = ClientTherapy::n()
            ->setProblemSeverity($request->input('therapy.problem_severity'))
            ->setPlan($request->input('therapy.plan'))
            ->setRequest($request->input('therapy.request'))
            ->setNotes($request->input('notes'))
            ->setConceptVision($request->input('concept_vision'));

        $therapy->client()->associate($client);

        if (!$therapy->save()) {
            throw new \RuntimeException('Ошибка создания данных терапии', JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
