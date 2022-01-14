<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest;

class TransformIndexRequest extends TransformsRequest
{
    /**
     * Transform the given value.
     *
     * @param  string  $key
     * @param  mixed  $value
     *
     * @return mixed
     * @throws \JsonException
     */
    protected function transform($key, $value): mixed
    {
        return $key === 'options' ?
            json_decode($value, true, 512, JSON_THROW_ON_ERROR) :
            $value;
    }
}
