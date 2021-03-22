<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\APIBaseController;
use App\Models\Api\Admin\Category;
use App\Models\Api\Admin\ConnectionType;
use App\Models\Api\Admin\User;
use Illuminate\Http\JsonResponse;

class StaticDataController extends APIBaseController
{
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
        return $this->sendResponse(['connection_types' => ConnectionType::query()->select(['id', 'name'])->get()]);
    }

    public function getMeetingTypes(): JsonResponse
    {
        $types = [
            ['id' => User::MEETING_TYPE_ONLINE, 'name' => 'Онлайн'],
            ['id' => User::MEETING_TYPE_OFFLINE, 'name' => 'Офлайн']
        ];

        return $this->sendResponse(['meeting_types' => $types]);
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
        $data = [
            ['id' => User::GENDER_FEMALE, 'name' => 'Женщина'],
            ['id' => User::GENDER_MALE, 'name' => 'Мужчина']
        ];

        return $this->sendResponse(['gender_list' => $data]);
    }

    /**
     * @return JsonResponse
     */
    public function getCategories(): JsonResponse
    {
        return $this->sendResponse([
            'categories' => Category::query()->select(['id', 'name'])->get()->keyBy('id')
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getUsers(): JsonResponse
    {
        return $this->sendResponse(['users' => User::query()->select(['id', 'name'])->get()]);
    }

    /**
     * Return all tooltips for admin tutorial-info
     *
     * @return JsonResponse
     */
    public function getTooltips(): JsonResponse
    {
        return $this->sendResponse([
            'doctor' => [
                'categories' => __('Personal categories')
            ]
        ]);
    }
}
