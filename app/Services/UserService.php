<?php

namespace App\Services;

use Illuminate\Support\Collection;

use App\Builders\FilterBuilder;

use App\Models\Client;

class UserService
{
    /**
     * @param $filters
     * @param $total
     *
     * @return Collection
     */
    public function getUsersWithFilters($filters, &$total): Collection
    {
        /** @var FilterBuilder $clientsQ */
        $clientsQ = Client::query()->with(['sessions', 'connectionType', 'therapy', 'category'])
            ->clientFilters($filters['fields'] ?? []);
        $total = $clientsQ->count();

        return $clientsQ->withPagination($filters['pagination'] ?? [])
            ->withTrashed()
            ->oldest('deleted_at')
            ->get();
    }
}
