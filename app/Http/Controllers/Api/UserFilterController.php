<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\{JsonResponse, Request};

use App\Contracts\ResponseContract;

use App\Models\UserFilter;

class UserFilterController extends Controller
{
    public function __construct(protected ResponseContract $json) {}

    public function get(string $module): JsonResponse
    {
        $filter = Auth::user()?->filters()->firstWhere('module', $module);
        if ($filter) {
            return $this->json->response(['filters' => $filter->value]);
        }

        return $this->json->response(['filters' => []]);
    }

    public function set(Request $request, string $module): JsonResponse
    {
        $filter = Auth::user()?->filters()->firstWhere('module', $module);
        if (!$filter) {
            $filter = new UserFilter();
            $filter->module = $module;
        }
        $filter->value = $request->input();
        $filter->user()->associate(Auth::user())->save();

        return $this->json->response(['filters' => []], 'Success');
    }

    public function clear(string $module = 'all'): JsonResponse
    {
        if ($module === 'all') {
            Auth::user()?->filters()->delete();

            return $this->json->response(['filters' => []], 'All filters cleaned');
        }
        Auth::user()?->filters()->where('module', $module)->delete();

        return $this->json->response(['filters' => []], 'Module filters cleaned');
    }
}
