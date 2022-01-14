<?php

namespace App\Http\Requests\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

use App\Helpers\PhoneHelper;

abstract class BaseApiRequest extends FormRequest
{
    /** @var array|string[] $email */
    protected array $email = ['sometimes', 'nullable', 'email:rfc,dns', 'max:255'];
    /** @var array|string[] $phone */
    protected array $phone = ['sometimes', 'nullable', 'string', 'max:18'];
    /** @var array|string[] $connectionTypeLink */
    protected array $connectionTypeLink = ['sometimes', 'string', 'nullable', 'max:255'];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    abstract public function rules(): array;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     *
     */
    protected function failedValidation(Validator $validator): void
    {
        $errors = (new ValidationException($validator))->errors();
        $message = (method_exists($this, 'message'))
            ? $this->container->call([$this, 'message'])
            : 'The given data was invalid.';
        if ($validator->fails()) {
            $message = $validator->errors()->first();
        }

        foreach ($errors as &$error) {
            $error = $error[0];
        }

        throw new HttpResponseException(new JsonResponse([
            'message' => $message,
            'errors'  => $errors,
            'data'    => (object) [],
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY, [], JSON_UNESCAPED_UNICODE));
    }

    protected function sanitize(): void
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
