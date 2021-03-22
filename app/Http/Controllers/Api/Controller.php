<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as BaseController;

/**
 * @OA\Info(
 *     title="Psych Manager Origin API",
 *     version="1.0 beta",
 *     @OA\Contact(email="info@ps.ru")
 * )
 * @OA\Server(
 *     description="PMO server",
 *     url="http://127.0.0.1:8000/api/v1"
 * )
 * @OA\SecurityScheme(
 *     type="apiKey",
 *     in="header",
 *     name="X-API-KEY",
 *     securityScheme="X-API-KEY"
 * )
 */
class Controller extends BaseController
{
}
