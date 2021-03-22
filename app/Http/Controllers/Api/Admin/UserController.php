<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\APIBaseController;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use App\Http\Requests\Api\Admin\UserRequest;

use App\Http\Resources\Admin\User\IndexResource;
use App\Http\Resources\Admin\User\ShowResource;

use App\Services\UserService;

use App\Models\Api\Admin\User;
use App\Models\Api\Admin\ConnectionType;
use App\Models\Api\Admin\UserTherapy;
use App\Models\Api\Admin\Category;

class UserController extends APIBaseController
{
    /**
     * @OA\Get(
     *     path="/users",
     *     operationId="users-index",
     *     tags={"Пользователи"},
     *     summary="Index page",
     *     security={
     *         {"X-ACCESS-TOKEN": {}}
     *     },
     *
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\JsonContent(ref="#/components/schemas/UserIndexResource")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Список пуст",
     *     ),
     * )
     *
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @param UserService $userService
     *
     * @return JsonResponse
     */
    public function index(Request $request, UserService $userService): JsonResponse
    {
        $filters = json_decode($request->input('filters'));
        $users = $userService->getUsersWithFilters($filters, $total);

        return $this->sendResponse(['users' => IndexResource::collection($users), 'total' => $total]);
    }

    /**
     * @OA\Post (
     *     path="/users",
     *     operationId="users-store",
     *     tags={"Пользователи"},
     *     summary="Store",
     *     security={
     *         {"X-ACCESS-TOKEN": {}}
     *     },
     *     @OA\RequestBody(
     *         required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/UserRequest"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response="200",
     *         description="Клиент добавлен",
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Ошибка создания",
     *     ),
     * )
     *
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     *
     * @return JsonResponse
     */
    public function store(UserRequest $request): JsonResponse
    {
        $user = new User($request->validated());
        $connectionType = ConnectionType::find($request->getConnectionType());
        $category = Category::find($request->getCategoryId());

        $user->doctor()->associate(Auth::user());
        $user->category()->associate($category);
        $connectionType->users()->save($user);

        $therapy = new UserTherapy([
            'problem_severity' => $request->input('therapy.problem_severity'),
            'plan' => $request->input('therapy.plan'),
            'request' => $request->input('therapy.request'),
            'notes' => $request->input('notes'),
            'concept_vision' => $request->input('concept_vision'),
        ]);
        if (!$user->save()) {
            return $this->sendError([], 'Ошибка сохранения клиента', JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        $therapy->user()->associate($user);

        if (!$therapy->save()) {
            return $this->sendError([], 'Ошибка создания данных терапии', JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->sendResponse([], 'Клиент добавлен', JsonResponse::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     operationId="users-show",
     *     tags={"Пользователи"},
     *     summary="Show page",
     *     security={
     *         {"X-ACCESS-TOKEN": {}}
     *     },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="id пользователя",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\JsonContent(ref="#/components/schemas/UserShowResource")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Клиент не найден",
     *     ),
     * )
     *
     * Display the specified resource.
     *
     * @param $id
     *
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $user = User::with('sessions', 'connectionType', 'category', 'therapy')
            ->withTrashed()
            ->find($id);
        if (!$user) {
            return $this->sendResponse([], 'Клиент не найден', JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->sendResponse(['user' => ShowResource::make($user)]);
    }

    /**
     * @OA\Put(
     *     path="/users/{id}",
     *     operationId="users-update",
     *     tags={"Пользователи"},
     *     summary="Update",
     *     security={
     *         {"X-ACCESS-TOKEN": {}}
     *     },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *              type="object",
     *              allOf={
     *                  @OA\Schema(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=5),
     *                  ),
     *                  @OA\Schema(ref="#/components/schemas/UserRequest"),
     *              }
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response="200",
     *         description="Клиент обновлён",
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Клиент не найден",
     *     ),
     * )
     *
     * Update the specified resource in storage.
     *
     * @param $id
     * @param UserRequest $request
     *
     * @throws \Throwable
     * @return JsonResponse
     */
    public function update($id, UserRequest $request): JsonResponse
    {
        $user = User::findOrFail($id);
        $connectionType = ConnectionType::find($request->getConnectionType());
        $user->update($request->validated());
        $category = Category::find($request->getCategoryId());

        try {
            DB::beginTransaction();

            $therapy = UserTherapy::updateOrCreate([
                'user_id' => $user->id
            ], [
                'plan' => $request->input('therapy.plan'),
                'notes' => $request->input('notes'),
                'request' => $request->input('therapy.request'),
                'problem_severity' => $request->input('therapy.problem_severity'),
                'concept_vision' => $request->input('concept_vision'),
            ]);
            $user->category()->associate($category);
            $therapy->user()->associate($user);
            $therapy->save();

            $connectionType->users()->save($user);
            if (!$user->save()) {
                return $this->sendError('Ошибка обновления клиента');
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        return $this->sendResponse([], 'Клиент обновлён');
    }

    /**
     * @OA\Delete (
     *     path="/users/{id}",
     *     operationId="users-delete",
     *     tags={"Пользователи"},
     *     summary="Delete",
     *     security={
     *         {"X-ACCESS-TOKEN": {}}
     *     },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="id пользователя",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response="200",
     *         description="Пользователь удалён",
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Пользователь не найден",
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Ошибка удаления",
     *     ),
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param $id
     *
     * @throws \Exception
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return $this->sendResponse([], 'Пользователь не найден', JsonResponse::HTTP_NOT_FOUND);
        }
        if (!$user->delete()) {
            return $this->sendError([], 'Ошибка удаления');
        }

        return $this->sendResponse([], 'Завершено');
    }
}
