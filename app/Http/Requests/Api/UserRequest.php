<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

use App\Models\Client;

class UserRequest extends BaseApiRequest
{
    public function rules(): array
    {
        $this->sanitize();
        $this->phone = ['required', 'string', 'max:18'];
        $this->email = ['sometimes', 'nullable', 'email:rfc,dns', 'max:255', 'unique:users'];
        if (\Auth::user()->phone != $this->input('phone')) {
            $this->phone = [
                'required', 'string', 'max:18',
                Rule::unique((new Client())->getTable(), 'phone')
            ];
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            // todo: check this
            'gender' => ['sometimes', 'nullable', 'boolean'],
            'connection_type_link' => $this->connectionTypeLink,
        ];
    }
}
