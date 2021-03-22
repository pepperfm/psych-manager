<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\APIBaseController;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Exception;

use App\Http\Requests\Api\Admin\SessionRequest;

use App\Http\Resources\Admin\Sessions\IndexResource;
use App\Http\Resources\Admin\Sessions\ShowResource;
use App\Http\Resources\Admin\Sessions\CalendarResource;

use App\Services\SessionService;

use App\Models\Api\Admin\Session;
use App\Models\Api\Admin\User;

class SessionController extends APIBaseController
{
    /**
     * @OA\Get(
     *     path="/sessions",
     *     operationId="sessions-index",
     *     tags={"Сессии"},
     *     summary="Index page",
     *     security={
     *         {"X-ACCESS-TOKEN": {}}
     *     },
     *
     *     @OA\Response(
     *         response="200",
     *         description="",
     *          @OA\JsonContent(ref="#/components/schemas/SessionIndexResource")
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
     * @param SessionService $sessionService
     *
     * @return JsonResponse
     */
    public function index(Request $request, SessionService $sessionService): JsonResponse
    {
        $filters = json_decode($request->input('filters'));
        $sessions = $sessionService->getSessionsWithFilters($filters, $total);

        return $this->sendResponse(['sessions' => IndexResource::collection($sessions), 'total' => $total]);
    }

    /**
     * @OA\Post (
     *     path="/sessions",
     *     operationId="sessions-store",
     *     tags={"Сессии"},
     *     summary="Store",
     *     security={
     *         {"X-ACCESS-TOKEN": {}}
     *     },
     *     @OA\RequestBody(
     *         required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/SessionRequest"),
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
     * @param SessionRequest $request
     *
     * @return JsonResponse
     */
    public function store(SessionRequest $request): JsonResponse
    {
        $session = new Session($request->validated());
        $user = User::find($request->getClientId());

        if (!$user->sessions()->save($session) || !$session->doctor()->associate(\Auth::user())->save()) {
            return $this->sendError([], 'Ошибка создания');
        }

        return $this->sendResponse([], 'Запись создана');
    }

    /**
     * @OA\Get(
     *     path="/sessions/{id}",
     *     operationId="sessions-show",
     *     tags={"Сессии"},
     *     summary="Show page",
     *     security={
     *         {"X-ACCESS-TOKEN": {}}
     *     },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="id сессии",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\JsonContent(ref="#/components/schemas/SessionShowResource")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Запись не найдена",
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
        $session = Session::find($id);
        if (!$session) {
            return $this->sendResponse([], 'Запись не найдена', JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->sendResponse(['session' => ShowResource::make($session)]);
    }

    /**
     * @OA\Put(
     *     path="/sessions/{id}",
     *     operationId="sessions-update",
     *     tags={"Сессии"},
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
     *                  @OA\Schema(ref="#/components/schemas/SessionRequest"),
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
     * @param SessionRequest $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function update($id, SessionRequest $request): JsonResponse
    {
        $session = Session::findOrFail($id);
        $session->update($request->validated());

        if (!$session->user->sessions()->save($session)) {
            return $this->sendResponse([], 'Запись не найдена', JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->sendResponse([], 'Сессия обновлена');
    }

    /**
     * @OA\Delete (
     *     path="/sessions/{id}",
     *     operationId="sessions-delete",
     *     tags={"Сессии"},
     *     summary="Delete",
     *     security={
     *         {"X-ACCESS-TOKEN": {}}
     *     },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="id сессии",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="cancel_reason", type="string", description="Причина отмены"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response="200",
     *         description="Запись отменена",
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Запись не найдена",
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Ошибка отмены",
     *     ),
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param $id
     *
     * @throws Exception
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $session = Session::find($id);
        if (!$session) {
            return $this->sendResponse([], 'Запись не найдена', JsonResponse::HTTP_NOT_FOUND);
        }
        if (!$session->delete()) {
            return $this->sendError([], 'Ошибка удаления');
        }

        return $this->sendResponse([], 'Запись отменена');
    }

    /**
     * @param SessionService $sessionService
     *
     * @return JsonResponse
     */
    public function getCalendarSessions(SessionService $sessionService): JsonResponse
    {
        $sessions = $sessionService->getLastMonthSessions();

        return $this->sendResponse(['sessions' => CalendarResource::collection($sessions)]);
    }
}
