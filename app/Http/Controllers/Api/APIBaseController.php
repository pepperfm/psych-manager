<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

class APIBaseController extends Controller
{
    /**
     * Success response method
     *
     * @param array|collection|JsonResource $data
     * @param $message
     * @param int $httpStatusCode
     *
     * @return JsonResponse
     */
    public function sendResponse($data, string $message = "", int $httpStatusCode = JsonResponse::HTTP_OK): JsonResponse
    {
        $response = [
            'message' => $message,
            'data'    => $data,
        ];

        return new JsonResponse($response, $httpStatusCode);
    }

    /**
     * Error response method
     *
     * @param $message
     * @param int $httpStatusCode
     * @param null $errors
     * @param null $data
     *
     * @return JsonResponse
     */
    public function sendError($message = "", int $httpStatusCode = JsonResponse::HTTP_INTERNAL_SERVER_ERROR, $errors = null, $data = null): JsonResponse
    {
        $response = [
            'message' => $message,
            'data'    => $data,
            'errors'  => $errors,
        ];

        return new JsonResponse($response, $httpStatusCode);
    }

    /**
     * Return error OAuth response.
     *
     * @param $message
     * @param string $errorType
     * @param int $httpStatusCode
     * @return JsonResponse
     */
    public function sendOAuthError($message = "",  $errorType = '', $httpStatusCode = JsonResponse::HTTP_BAD_REQUEST): JsonResponse
    {
        $response = [
            'error'             => $errorType,
            'error_description' => $message,
            'message'           => $message,
        ];

        return new JsonResponse($response, $httpStatusCode);
    }
}
