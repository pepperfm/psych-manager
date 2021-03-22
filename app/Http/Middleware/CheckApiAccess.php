<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Api\Admin\Doctor;

class CheckApiAccess
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasHeader('X-API-KEY')) {
            return new JsonResponse(['message' => 'Пользователь не авторизован'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $token = $request->header('X-API-KEY');
        $doctor = Doctor::where('auth_token', $token)
            ->where('auth_token_expire', '>', (new \DateTime)->format('Y-m-d H:i:s'))
            ->first();

        if (!$doctor) {
            return new JsonResponse(['message' => 'Пользователь не найден или истек срок действия сессии'], JsonResponse::HTTP_FORBIDDEN);
        }

        Auth::loginUsingId($doctor->id);

        return $next($request);
    }
}
