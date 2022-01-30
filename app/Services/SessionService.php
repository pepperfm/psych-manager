<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

use Carbon\Carbon;

use App\Builders\FilterBuilder;

use App\Models\Session;

class SessionService
{

    /**
     * @param array $filters
     * @param int|null $total
     * @param User $user
     *
     * @return Collection
     */
    public function getSessionsWithFilters(array $filters, int|null &$total, User $user): Collection
    {
        /** @var FilterBuilder $sessionsQ */
        $sessionsQ = $user->sessions()->with(['client'])->sessionFilters($filters['fields'] ?? []);
        $total = $sessionsQ->count();

        return $sessionsQ->withPagination($filters['pagination'] ?? [])
            ->sort($filters['sort'] ?? [])
            ->get();
    }

    /**
     * @return Session[]|Collection
     */
    public function getLastMonthSessions(): array|Collection
    {
        $now = Carbon::now();
        $date = Carbon::createFromDate($now->year, $now->month);

        return Session::q()->with(['client'])->whereDate('session_date', '>=', $date)->get();
    }
}
