<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\{ Auth, DB, Log };

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Http\Requests\Api\ClientRequest;

use App\Http\Resources\Api\Client\{ IndexResource, ShowResource };

use App\Contracts\ResponseContract;

use App\Services\UserService;

use App\Models\{ ConnectionType, Category, Client };

class ClientController extends Controller
{
    public function __construct(protected ResponseContract $json) {}

    /**
     * @OA\Get(
     *     path="/clients",
     *     operationId="clients-index",
     *     tags={"Клиенты"},
     *     summary="Index page",
     *     security={
     *         {"X-ACCESS-TOKEN": {}}
     *     },
     *
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\JsonContent(ref="#/components/schemas/ClientIndexResource")
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
        $filters = $request->input('options', []);
        $clients = $userService->getUsersWithFilters($filters, $total);

        return $this->json->response(['clients' => IndexResource::collection($clients), 'total' => $total]);
    }

    /**
     * @OA\Post (
     *     path="/clients",
     *     operationId="clients-store",
     *     tags={"Клиенты"},
     *     summary="Store",
     *     security={
     *         {"X-ACCESS-TOKEN": {}}
     *     },
     *     @OA\RequestBody(
     *         required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/ClientRequest"),
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
     * @param ClientRequest $request
     *
     * @return JsonResponse
     */
    public function store(ClientRequest $request): JsonResponse
    {
        $client = new Client($request->validated());
        $connectionType = ConnectionType::find($request->getConnectionType());
        $category = Category::find($request->getCategoryId());

        $client->user()->associate(Auth::user());
        $client->category()->associate($category);
        $connectionType->clients()->save($client);

        $therapy = ClientTherapy::n()
            ->setProblemSeverity($request->input('therapy.problem_severity'))
            ->setPlan($request->input('therapy.plan'))
            ->setRequest($request->input('therapy.request'))
            ->setNotes($request->input('notes'))
            ->setConceptVision($request->input('concept_vision'));
        // ([
        //     'problem_severity' => $request->input('therapy.problem_severity'),
        //     'plan' => $request->input('therapy.plan'),
        //     'request' => $request->input('therapy.request'),
        //     'notes' => $request->input('notes'),
        //     'concept_vision' => $request->input('concept_vision'),
        // ]);
        if (!$client->save()) {
            return $this->json->error('Ошибка сохранения клиента');
        }

        $therapy->client()->associate($client);

        if (!$therapy->save()) {
            return $this->json->error('Ошибка создания данных терапии');
        }

        return $this->json->response([], 'Клиент добавлен', JsonResponse::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/clients/{id}",
     *     operationId="clients-show",
     *     tags={"Клиенты"},
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
     *         @OA\JsonContent(ref="#/components/schemas/ClientShowResource")
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
        $client = Client::withTrashed()
            ->with(['sessions', 'connectionType', 'category', 'therapy'])
            ->find($id);
        if (!$client) {
            return $this->json->response([], 'Клиент не найден', JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->json->response(['client' => ShowResource::make($client)]);
    }

    /**
     * @OA\Put(
     *     path="/clients/{id}",
     *     operationId="clients-update",
     *     tags={"Клиенты"},
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
     *                  @OA\Schema(ref="#/components/schemas/ClientRequest"),
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
     * @param ClientRequest $request
     *
     * @throws \Throwable
     * @return JsonResponse
     */
    public function update($id, ClientRequest $request): JsonResponse
    {
        $client = Client::findOrFail($id);
        $connectionType = ConnectionType::find($request->getConnectionType());
        $client->update($request->validated());
        $category = Category::find($request->getCategoryId());

        try {
            DB::beginTransaction();

            $therapy = ClientTherapy::updateOrCreate([
                'client_id' => $client->id
            ], [
                'plan' => $request->input('therapy.plan'),
                'notes' => $request->input('notes'),
                'request' => $request->input('therapy.request'),
                'problem_severity' => $request->input('therapy.problem_severity'),
                'concept_vision' => $request->input('concept_vision'),
            ]);
            $client->category()->associate($category);
            $therapy->client()->associate($client);
            $therapy->save();

            $connectionType->clients()->save($client);
            $client->save();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::critical($e);

            return $this->json->error(
                'Ошибка обновления клиента',
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                $e->getMessage()
            );
        }

        return $this->json->response([], 'Клиент обновлён');
    }

    /**
     * @OA\Delete (
     *     path="/clients/{id}",
     *     operationId="clients-delete",
     *     tags={"Клиенты"},
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
     *         description="Клиент удалён",
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Клиент не найден",
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
        $client = Client::find($id);
        if (!$client) {
            return $this->json->response([], 'Пользователь не найден', JsonResponse::HTTP_NOT_FOUND);
        }
        if (!$client->delete()) {
            return $this->json->error('Ошибка удаления');
        }

        return $this->json->response([], 'Завершено');
    }
}
