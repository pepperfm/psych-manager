<?php

namespace App\Services;

use Illuminate\Support\Collection;

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
        $clients = Client::with(['sessions', 'connectionType', 'therapy', 'category'])
            ->clientFilters($filters['fields'] ?? []);
        $total = $clients->count();

        return $clients->withPagination($filters['pagination'] ?? [])
            ->withTrashed()
            ->oldest('deleted_at')
            ->get();
    }
}
