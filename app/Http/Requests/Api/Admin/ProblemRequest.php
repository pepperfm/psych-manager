<?php

namespace App\Http\Requests\Api\Admin;

class ProblemRequest extends BaseAdminRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer']
        ];
    }
}
