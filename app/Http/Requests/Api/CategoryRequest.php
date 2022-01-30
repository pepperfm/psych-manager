<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

/**
 * Class CategoryRequest
 * @package App\Http\Requests\Api
 *
 * @OA\Schema(schema="CategoryRequest",
 *     @OA\Property(property="name", type="string", description="Category name")
 * )
 */
class CategoryRequest extends BaseApiRequest
{
    public function rules(): array
    {
        return [
            'categories' => [Rule::requiredIf(!empty($this->input('categories'))), 'nullable', 'array'],
            'categories.*' => [Rule::requiredIf(!empty($this->input('categories'))), 'nullable', 'integer', 'exists:categories,id'],
            'name' => [Rule::requiredIf($this->routeIs('categories.store')), 'string', 'max:255']
        ];
    }
}
