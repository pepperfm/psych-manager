<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;

use Exception;

use App\Http\Requests\Api\CategoryRequest;
use App\Http\Resources\Api\CategoryResource;

use App\Contracts\ResponseContract;

use App\Models\Category;

class CategoryController extends Controller
{
    public function __construct(protected ResponseContract $json) {}

    /**
     * @OA\Get(
     *     path="/categories",
     *     operationId="categories-index",
     *     tags={"Категории"},
     *     summary="Index page",
     *     security={
     *         {"X-ACCESS-TOKEN": {}}
     *     },
     *
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Список пуст",
     *     ),
     * )
     *
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->json->response(['categories' => CategoryResource::collection(Category::all())]);
    }

    /**
     * @OA\Post (
     *     path="/categories",
     *     operationId="categories-store",
     *     tags={"Категории"},
     *     summary="Store",
     *     security={
     *         {"X-ACCESS-TOKEN": {}}
     *     },
     *     @OA\RequestBody(
     *         required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/CategoryRequest"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response="200",
     *         description="Категория добавлена",
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Ошибка создания",
     *     ),
     * )
     *
     * Store a newly created resource in storage.
     *
     * @param CategoryRequest $request
     *
     * @return JsonResponse
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        Category::n()->setName($request->input('name'))->save();

        return $this->json->response([], 'Категория добавлена', JsonResponse::HTTP_CREATED);
    }

    /**
     * @deprecated
     * @OA\Get(
     *     path="/categories/{id}",
     *     operationId="categories-show",
     *     tags={"Категории"},
     *     summary="Show page",
     *     security={
     *         {"X-ACCESS-TOKEN": {}}
     *     },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="id категории",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response="200",
     *         description="Название категории",
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Категория не найдена",
     *     ),
     * )
     *
     * Display the specified resource.
     *
     * @param Category $category
     *
     * @return JsonResponse
     */
    public function show(Category $category): JsonResponse
    {
        return $this->json->response(['category' => $category]);
    }

    /**
     * @deprecated
     * @OA\Put(
     *     path="/categories/{id}",
     *     operationId="categories-update",
     *     tags={"Категории"},
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
     *                  @OA\Schema(ref="#/components/schemas/CategoryRequest"),
     *              }
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response="200",
     *         description="Категория обновлена",
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Категория не найдена",
     *     ),
     * )
     *
     * Update the specified resource in storage.
     *
     * @param CategoryRequest $request
     * @param Category $category
     *
     * @return JsonResponse
     */
    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        if (!$category->setName($request->input('name'))->save()) {
            return $this->json->response([], 'Ошибка сохранения', JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json->response([], 'Категория обновлена');
    }

    /**
     * @OA\Delete (
     *     path="/categories/{id}",
     *     operationId="categories-delete",
     *     tags={"Категории"},
     *     summary="Delete",
     *     security={
     *         {"X-ACCESS-TOKEN": {}}
     *     },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="id категории",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response="200",
     *         description="Успешно",
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Категория не найдена",
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Ошибка удаления",
     *     ),
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param Category $category
     *
     * @throws Exception
     * @return JsonResponse
     */
    public function destroy(Category $category): JsonResponse
    {
        if (!$category->delete()) {
            return $this->json->error('Ошибка удаления');
        }

        return $this->json->response([], 'Успешно');
    }
}
