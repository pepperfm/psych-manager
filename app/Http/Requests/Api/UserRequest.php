<?php

namespace App\Http\Requests\Api;

class UserRequest extends BaseApiRequest
{
    public function rules(): array
    {
        $this->sanitize();
        $this->phone = ['required', 'string', 'max:18'];
        $this->email = ['sometimes', 'nullable', 'email:rfc,dns', 'max:255', 'unique:users,email'];
        if (\Auth::user()->phone != $this->input('phone')) {
            $this->phone = ['required', 'string', 'max:18', 'unique:users,phone'];
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['sometimes', 'nullable', 'boolean'],
            'connection_type_link' => $this->connectionTypeLink,
        ];
    }
}
