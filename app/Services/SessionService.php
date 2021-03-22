<?php

namespace App\Services;

use Illuminate\Support\Collection;

use Carbon\Carbon;

use App\Models\Api\Admin\Session;

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
        $sessions = Session::with('user')->withFilters($filters);
        $total = $sessions->count();

        return $sessions->paginationApi($filters)->get();
    }

    /**
     * @return Session[]|Collection
     */
    public function getLastMonthSessions()
    {
        $now = Carbon::now();
        $date = Carbon::createFromDate($now->year, $now->month);

        return Session::with('user')->whereDate('session_date', '>=', $date)->get();
    }
}
