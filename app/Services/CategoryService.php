<?php

namespace App\Services;

use App\Http\Resources\Api\CategoryResource;

use Illuminate\Support\Collection;

use App\Models\Category;

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
        return Category::q()
            ->select(['id', 'name'])
            ->get()
            // ->groupBy(fn($item) => $item->group->name)
            // ->map(fn($category) => CategoryResource::make($category->map(fn($item) => $item->unsetRelation('group'))));
            ->map(fn($category) => CategoryResource::make($category));
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
