<?php

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;

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
        $order = ($sort['order'] ?? 0) === 1 ? 'asc' : 'desc';
        $this->orderBy($this->resolveSortField($sort['field'] ?? 'id'), $order);

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
                fn() => $this->whereHas('connectionType',
                    fn() => $this->where('id', $filters['connection_type'])
                )
            )
            ->when($filters['phone'] ?? false,
                fn() => $this->where('phone', 'like', "%{$filters['phone']}%")
            )
            ->when($filters['date_range'] ?? false,
                fn() => $this->whereHas('sessions',
                    fn() => $this->whereBetween('session_date', [$filters['date_range'][0], $filters['date_range'][1]])
                )
            );

        return $this;
    }
}