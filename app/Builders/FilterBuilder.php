<?php

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;

use App\Models\Session;

class FilterBuilder extends Builder
{
    /**
     * @param array $pagination
     *
     * @return Builder
     */
    public function withPagination(array $pagination = []): Builder
    {
        $offset = ( ( $pagination['page'] ?? 1 ) - 1 ) * ($pagination['limit'] ?? 50);

        $this->offset($offset)->limit($pagination['limit'] ?? 50);

        return $this;
    }

    /**
     * @param array $sort
     *
     * @return Builder
     */
    public function sort(array $sort = []): Builder
    {
        $direction = $sort['order'] ? 'asc' : 'desc';
        if ($sort['field'] !== 'session_date_users') {
            $this->orderBy($this->resolveSortField($sort['field'] ?? 'id'), $direction);
        } else {
            // $this->whereHas('sessions', function ($q) use ($order) {
            //     return $q->orderBy('session_date', $order)->dd();
            // });
            // dd(
            //     $this->select('clients.id')->join('sessions', 'sessions.client_id', '=', 'clients.id')
            //         ->select('sessions.session_date')
            //         ->orderBy('sessions.session_date', $order);
            // );
            // dd(
            $this->orderBy(
                Session::select(['session_date'])
                    ->whereColumn('sessions.client_id', 'clients.id')
                    ->latest('session_date')
                    ->take(1),
                $direction
            );
            // );
            // $this->whereHas('sessions', fn($q) => $q->orderBy('session_date', $order));
            // $this->orderBy(
            //     $this-whereHas('sessions', fn($q) => $q->latest('session_date')),
            //     $order
            // );
        }

        return $this;
    }

    /**
     * @param string $field
     *
     * @return string
     */
    public function resolveSortField(string $field): string
    {
        return match ($field) {
            'id' => 'id',
            default => $field,
        };
    }

    /**
     * @param array $filters
     *
     * @return Builder
     */
    public function clientFilters(array $filters = []): Builder
    {
        $meetingType = !empty($filters['meeting_type']) || $filters['meeting_type'] === 0;
        $categoryId = !empty($filters['category_id']) || $filters['category_id'] === 0;
        $connectionType = !empty($filters['connection_type']) || $filters['connection_type'] === 0;

        $this
            ->when($meetingType, fn() => $this->where('meeting_type', $filters['meeting_type']))
            ->when($categoryId, fn() => $this->where('category_id', $filters['category_id']))
            ->when($filters['name'] ?? false,
                fn() => $this->where('name', 'like', "%{$filters['name']}%")
            )
            ->when($filters['email'] ?? false,
                fn() => $this->where('email', 'like', "%{$filters['email']}%")
            )
            ->when($connectionType,
                fn() => $this->whereHas('connectionType', function ($q) use ($filters) {
                    return $q->where('id', $filters['connection_type']);
                })
            )
            ->when($filters['phone'] ?? false,
                fn() => $this->where('phone', 'like', "%{$filters['phone']}%")
            )
            ->when($filters['date_range'] ?? false,
                fn() => $this->whereHas('sessions',
                    function ($q) use ($filters) {
                        return $q->whereBetween('session_date', [$filters['date_range'][0], $filters['date_range'][1]]);
                    })
            );

        return $this;
    }

    /**
     * @param array $filters
     *
     * @return Builder
     */
    public function sessionFilters(array $filters = []): Builder
    {
        $meetingType = !empty($filters['meeting_type']) || $filters['meeting_type'] === 0;
        $connectionType = !empty($filters['connection_type']) || $filters['connection_type'] === 0;

        $this
            ->when($filters['date_range'] ?? false,
                fn() => $this->whereBetween('session_date', [$filters['date_range'][0], $filters['date_range'][1]])
            )
            ->when($meetingType,
                fn() => $this->whereHas('client', function ($q) use ($filters) {
                    return $q->where('meeting_type', $filters['meeting_type']);
                })
            )
            ->when($connectionType,
                fn() => $this->whereHas('client', function ($q) use ($filters) {
                    return $q->whereHas('connectionType', function ($qq) use ($filters) {
                        return $qq->where('id', $filters['connection_type']);
                    });
                })
            )
            ->when($filters['user_name'] ?? false,
                fn() => $this->whereHas('client', function ($q) use ($filters) {
                    return $q->where('name', 'like', "%{$filters['user_name']}%");
                })
            );

        return $this;
    }
}
