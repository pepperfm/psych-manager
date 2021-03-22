<?php

namespace App\Models\Api\Admin;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

use App\Models\Scopes\SessionUserScope;
use App\Traits\PaginationTrait;

use App\Models\User as BaseUser;

/**
 * App\Models\Api\Admin\User
 *
 * @method Builder withFilters()
 * @method Builder paginationApi()
 * @method Builder withTrashed()
 */
class User extends BaseUser
{
    use PaginationTrait;

    protected $table = 'users';

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
        $categoryId = !empty($filters->fields->category_id) || $filters->fields->category_id === 0;
        $connectionType = !empty($filters->fields->connection_type) || $filters->fields->connection_type === 0;

        $query
            ->when($meetingType, function ($q) use ($filters) {
                return $q->where('meeting_type', $filters->fields->meeting_type);
            })
            ->when($categoryId, function ($q) use ($filters) {
                return $q->where('category_id', $filters->fields->category_id);
            })
            ->when(!empty($filters->fields->name), function ($q) use ($filters) {
                return $q->where('name', 'like', "%{$filters->fields->name}%");
            })
            ->when(!empty($filters->fields->email), function ($q) use ($filters) {
                return $q->where('email', 'like', "%{$filters->fields->email}%");
            })
            ->when($connectionType, function ($q) use ($filters) {
                return $q->whereHas('connectionType', function ($qq) use ($filters) {
                    return $qq->where('id', $filters->fields->connection_type);
                });
            })
            ->when(!empty($filters->fields->phone), function ($q) use ($filters) {
                return $q->where('phone', 'like', "%{$filters->fields->phone}%");
            })
            ->when(!empty($filters->fields->date_range), function ($q) use ($filters) {
                $q->whereHas('sessions', function ($qq) use ($filters) {
                    return $qq->whereBetween('session_date', [$filters->fields->date_range[0], $filters->fields->date_range[1]]);
                });
            });

        return $query;
    }
}
