<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;

use App\Http\Resources\Api\User\UserResource;

use App\Http\Requests\Api\{ CategoryRequest, UserRequest };

use App\Contracts\ResponseContract;

use App\Models\User;

class UserController extends Controller
{
    public function __construct(protected ResponseContract $json) {}

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $user = User::find(\Auth::id())?->load(['categories']);

        return $this->json->response(['user' => UserResource::make($user)]);
    }

    /**
     * Sync categories
     *
     * @param CategoryRequest $request
     *
     * @return JsonResponse
     */
    public function syncCategories(CategoryRequest $request): JsonResponse
    {
        \Auth::user()?->categories()->sync($request->input('categories'));

        return $this->json->response([], 'Сохранено', JsonResponse::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param User $user
     *
     * @return JsonResponse
     */
    public function update(UserRequest $request, User $user): JsonResponse
    {
        if (!$user->update($request->validated())) {
            return $this->json->error('', JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->json->response([], 'Данные обновлены');
    }
}
