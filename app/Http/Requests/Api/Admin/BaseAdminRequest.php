<?php

namespace App\Http\Requests\Api\Admin;

use App\Http\Requests\Api\BaseApiRequest;

use Illuminate\Support\Facades\Auth;

use App\Helpers\PhoneHelper;

abstract class BaseAdminRequest extends BaseApiRequest
{
    /** @var array|string[] $email */
    protected array $email = ['sometimes', 'nullable', 'email:rfc,dns', 'max:255'];
    /** @var array|string[] $phone */
    protected array $phone = ['sometimes', 'nullable', 'string', 'max:18'];
    /** @var array|string[] $connectionTypeLink */
    protected array $connectionTypeLink = ['sometimes', 'string', 'nullable', 'max:255'];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    protected function sanitize()
    {
        $inputs = $this->all();
        $inputs['phone'] = PhoneHelper::clear($inputs['phone']);
        $this->replace($inputs);
    }

    public function messages(): array
    {
        return [
            'phone.unique' => __('This phone already exists'),
            'connection_type_id.required' => __('Please, select connection type'),
        ];
    }
}
