<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;

use App\Http\Requests\Api\AuthRequest;

use App\Models\Api\Admin\User;

class AuthController extends APIBaseController
{
    /**
     * @OA\Post(
     *     path="/auth",
     *     operationId="Auth",
     *     tags={"Аутентификация"},
     *     summary="Логин",
     *     @OA\RequestBody(
     *         required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/AuthRequest"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response="200",
     *         description="Успешно",
     *         @OA\JsonContent(
     *             @OA\Property(property="auth_token", type="string", example="CyQaejvE9Tq2ykXW1aCz4aYpxU8OEpJngkVWjpHj"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Введите email",
     *     ),
     * )
     *
     *
     * @param AuthRequest $request
     * @return JsonResponse
     */
    public function auth(AuthRequest $request)
    {
        if (!$request->input('email')) {
            return $this->sendResponse([], 'Введите email', JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        $email = $request->getEmail();
        $user = User::findByEmail($email);

        if (!$user) {
            $user = User::createNew($email, $request->getPassword());
        }

        $user->regenerateAuthToken();

        return $this->sendResponse(['token' => $user->auth_token], 'Успешно');
    }
}
