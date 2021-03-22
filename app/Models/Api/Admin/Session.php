<?php

namespace App\Models\Api\Admin;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

use App\Models\Scopes\SessionUserScope;
use App\Traits\PaginationTrait;

use App\Models\Session as BaseSession;

/**
 * Class Session
 * @package App\Models\Api\Admin
 */
class Session extends BaseSession
{
    use PaginationTrait;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new SessionUserScope(Auth::id() ?? 1));
    }

    /**
     * @param Builder $query
     * @param $filters
     *
     * @return Builder
     */
    public function scopeWithFilters(Builder $query, $filters): Builder
    {
        $meetingType = !empty($filters->fields->meeting_type) || $filters->fields->meeting_type === 0;
        $connectionType = !empty($filters->fields->connection_type) || $filters->fields->connection_type === 0;

        $query
            ->when(!empty($filters->fields->date_range), function ($q) use ($filters) {
                return $q->whereBetween('session_date', [$filters->fields->date_range[0], $filters->fields->date_range[1]]);
            })
            ->when($meetingType, function ($q) use ($filters) {
                return $q->whereHas('user', function ($qq) use ($filters) {
                    return $qq->where('meeting_type', $filters->fields->meeting_type);
                });
            })
            ->when($connectionType, function ($q) use ($filters) {
                return $q->whereHas('user', function ($qq) use ($filters) {
                    return $qq->whereHas('connectionType', function ($qqq) use ($filters) {
                        return $qqq->where('id', $filters->fields->connection_type);
                    });
                });
            })
            ->when(!empty($filters->fields->user_name), function ($q) use ($filters) {
                return $q->whereHas('user', function ($qq) use ($filters) {
                    return $qq->where('name', 'like', "%{$filters->fields->user_name}%");
                });
            });

        return $query;
    }
}
