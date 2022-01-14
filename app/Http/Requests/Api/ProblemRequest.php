<?php

namespace App\Http\Requests\Api;

class ProblemRequest extends BaseApiRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer']
        ];
    }
}
