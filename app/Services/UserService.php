<?php

namespace App\Services;

use Illuminate\Support\Collection;

use App\Models\User;

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
        $users = User::with(['sessions', 'connectionType', 'therapy', 'category'])
            ->withFilters($filters);
        $total = $users->count();

        return $users->paginationApi($filters)->withTrashed()->oldest('deleted_at')->get();
    }
}
