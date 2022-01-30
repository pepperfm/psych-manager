<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;

use App\Contracts\ResponseContract;

use App\Enums\{Enum, ConnectionTypeEnum, GenderEnum, MeetingTypeEnum};

use App\Models\{Category, Client};

class StaticDataController extends Controller
{
    public function __construct(protected ResponseContract $json) {}

    /**
     * @OA\Get(
     *     path="/static-data/get-connection-type",
     *     operationId="get-connection-type",
     *     tags={"Статические данные"},
     *     summary="Connection types",
     *     security={
     *         {"X-ACCESS-TOKEN": {}}
     *     },
     *
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Список пуст",
     *     ),
     * )
     *
     * @return JsonResponse
     */
    public function getConnectionTypes(): JsonResponse
    {
        return $this->json->response([
            'connection_types' => Enum::makeList(ConnectionTypeEnum::cases())
        ]);
    }

    public function getMeetingTypes(): JsonResponse
    {
        return $this->json->response(['meeting_types' => Enum::makeList(MeetingTypeEnum::cases())]);
    }

    /**
     * @OA\Get(
     *     path="/static-data/get-gender-list",
     *     operationId="get-gender-list",
     *     tags={"Статические данные"},
     *     summary="Gender list",
     *     security={
     *         {"X-ACCESS-TOKEN": {}}
     *     },
     *
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Список пуст",
     *     ),
     * )
     *
     * @return JsonResponse
     */
    public function getGenderList(): JsonResponse
    {
        return $this->json->response(['gender_list' => Enum::makeList(GenderEnum::cases())]);
    }

    /**
     * @return JsonResponse
     */
    public function getCategories(): JsonResponse
    {
        return $this->json->response([
            'categories' => Category::q()->select(['id', 'name'])->get()->keyBy('id')
        ]);
    }

    /**
     * Return all tooltips for admin tutorial-info
     *
     * @return JsonResponse
     */
    public function getTooltips(): JsonResponse
    {
        return $this->json->response([
            'user' => [
                'categories' => __('Personal categories')
            ]
        ]);
    }
}
