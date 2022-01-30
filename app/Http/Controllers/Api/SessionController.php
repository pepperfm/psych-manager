<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\{JsonResponse, Request};

use Exception;

use App\Http\Requests\Api\SessionRequest;

use App\Http\Resources\Api\Sessions\{IndexResource, ShowResource, CalendarResource};

use App\Contracts\ResponseContract;

use App\Services\SessionService;

use App\Models\{Session, Client};

class SessionController extends Controller
{
    public function __construct(protected ResponseContract $json) {}

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
        $sessions = $sessionService->getSessionsWithFilters($request->input('options', []), $total);

        return $this->json->response(['sessions' => IndexResource::collection($sessions), 'total' => $total]);
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
        $session = Session::n()
            ->setComment($request->input('comment'))
            ->setSessionDate($request->input('session_date'));

        $client = Client::find($request->getClientId());

        if (!$client->sessions()->save($session) || !$session->user()->associate(\Auth::user())->save()) {
            return $this->json->error('Ошибка создания');
        }

        return $this->json->response([], 'Запись создана');
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
        $session = Session::q()->find($id);
        if (!$session) {
            return $this->json->response([], 'Запись не найдена', JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->json->response(['session' => ShowResource::make($session)]);
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
        // TODO: check this
        $session->setComment($request->input('comment'))
            ->setSessionDate($request->input('session_date'))
            ->save();

        if (!\Auth::user()?->sessions()->save($session)) {
            return $this->json->response([], 'Запись не найдена', JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->json->response([], 'Сессия обновлена');
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
        $session = Session::q()->find($id);
        if (!$session) {
            return $this->json->response([], 'Запись не найдена', JsonResponse::HTTP_NOT_FOUND);
        }
        if (!$session->delete()) {
            return $this->json->error('Ошибка удаления');
        }

        return $this->json->response([], 'Запись отменена');
    }

    /**
     * @param SessionService $sessionService
     *
     * @return JsonResponse
     */
    public function getCalendarSessions(SessionService $sessionService): JsonResponse
    {
        $sessions = $sessionService->getLastMonthSessions();

        return $this->json->response(['sessions' => CalendarResource::collection($sessions)]);
    }

    public function clone($id): void
    {
        $session = Session::findOrFail($id);
        $newRecord = $session->duplicate(); //->save()
        $newRecord->save();
    }
}
