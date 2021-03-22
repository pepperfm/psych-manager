<?php

namespace App\Traits;

use App\Models\Api\Admin\Session;

trait PaginationTrait
{
    public function scopePaginationApi($query, $filters)
    {
        // TODO: after last changes this filter wont work
        if ($filters->sort->field == "session_date_users") {
            return $query->limit($filters->pagination->limit ?? 15)
                ->offset($this->getOffset($filters->pagination->page, $filters->pagination->limit ?? 15))
                ->orderBy(Session::select(['session_date'])
                    ->whereColumn('sessions.user_id', 'users.id')
                    ->latest('session_date'),
                    $filters->sort->order ? 'ASC' : 'DESC'
                );
                // ray(
                //     Session::withoutGlobalScopes()
                //         ->select(['session_date'])
                //         ->whereColumn('sessions.user_id', 'users.id')
                //         ->get()
                // );
                // ray()->pause();
                // ->orderBy(Session::select(['session_date'])
                //     ->whereColumn('sessions.user_id', 'users.id')
                //     ->latest('session_date'),
                //     $filters->sort->order ? 'ASC' : 'DESC'
                // );
        }

        return $query->limit($filters->pagination->limit ?? 15)
            ->offset($this->getOffset($filters->pagination->page, $filters->pagination->limit ?? 15))
            ->orderBy($filters->sort->field, $filters->sort->order ? 'ASC' : 'DESC');
    }

    public function getOffset(int $page, int $limit)
    {
        return $page == 1 ? 0 : ($page - 1) * $limit;
    }
}
