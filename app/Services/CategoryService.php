<?php

namespace App\Services;

use App\Http\Resources\Admin\CategoryResource;

use Illuminate\Support\Collection;

use App\Models\Api\Admin\Category;

/**
 * @deprecated
 * Class CategoryService
 * @package App\Services\
 */
class CategoryService
{
    /**
     * @return Collection
     */
    public function combine(): Collection
    {
        return Category::query()
            ->with('group')
            ->select(['id', 'group_id', 'name'])
            ->get()
            ->groupBy(fn($item) => $item->group->name)
            ->map(function ($category) {
                return CategoryResource::collection($category->map(fn($item) => $item->unsetRelation('group')));
            });
    }

    /**
     * @return Collection
     */
    public function getCategoriesSelect(): Collection
    {
        $categories = collect();
        foreach ($this->combine() as $key => $item) {
            $categories->push(['label' => $key, 'options' => $item]);
        }

        return $categories;
    }
}
