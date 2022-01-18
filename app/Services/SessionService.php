<?php

namespace App\Services;

use Illuminate\Support\Collection;

use Carbon\Carbon;

use App\Builders\FilterBuilder;

use App\Models\Session;

class SessionService
{
    /**
     * @param $filters
     * @param $total
     *
     * @return Collection
     */
    public function getSessionsWithFilters($filters, &$total): Collection
    {
        /** @var FilterBuilder $sessionsQ */
        $sessionsQ = Session::q()->with(['user'])->withFilters($filters['fields'] ?? []);
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

        return Session::with(['client'])->whereDate('session_date', '>=', $date)->get();
    }
}
