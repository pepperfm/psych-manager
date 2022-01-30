<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\{Auth, DB, Log};

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Http\Requests\Api\ClientRequest;

use App\Http\Resources\Api\Client\{IndexResource, ShowResource};

use App\Contracts\ResponseContract;

use App\Services\ClientService;

use App\Models\{ClientTherapy, ConnectionType, Category, Client};

class ClientController extends Controller
{
    public function __construct(
        protected ResponseContract $json,
        protected ClientService $clientService
    ) {}

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
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->input('options', []);
        $clients = $this->clientService->getUsersWithFilters($filters, $total, Auth::user());

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
     * @throws \Throwable
     * @return JsonResponse
     */
    public function store(ClientRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $this->clientService->save($request, Auth::user());

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return $this->json->response([], $e->getMessage(), $e->getCode());
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
        $client = Client::query()->withTrashed()
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
        try {
            DB::beginTransaction();

            $client = Client::findOrFail($id);
            $connectionType = ConnectionType::find($request->getConnectionType());
            $client->update($request->validated());
            $category = Category::find($request->getCategoryId());

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

    /**
     * @return JsonResponse
     */
    public function getSessionClients(): JsonResponse
    {
        return $this->json->response(['clients' => Auth::user()?->clients()->select(['id', 'name'])->get()]);
    }
}
