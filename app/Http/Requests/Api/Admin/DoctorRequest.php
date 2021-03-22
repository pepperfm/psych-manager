<?php

namespace App\Http\Requests\Api\Admin;

use Illuminate\Validation\Rule;

use App\Models\Api\Admin\Doctor;

class DoctorRequest extends BaseAdminRequest
{
    public function rules(): array
    {
        $this->sanitize();
        $this->phone = ['required', 'string', 'max:18'];
        $this->email = ['sometimes', 'nullable', 'email:rfc,dns', 'max:255', 'unique:users'];
        if (\Auth::user()->phone != $this->input('phone')) {
            $this->phone = [
                'required', 'string', 'max:18',
                Rule::unique((new Doctor())->getTable(), 'phone')
            ];
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', Rule::in([Doctor::GENDER_FEMALE, Doctor::GENDER_MALE])],
            'connection_type_link' => $this->connectionTypeLink,
            'group_id' => ['required', 'integer', Rule::in(Doctor::query()->get()->pluck('id')->toArray())]
        ];
    }
}
