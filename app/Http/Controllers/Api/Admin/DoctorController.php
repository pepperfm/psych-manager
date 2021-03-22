<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\APIBaseController;

use Illuminate\Http\JsonResponse;

use App\Http\Requests\Api\Admin\DoctorRequest;
use App\Http\Requests\Api\Admin\CategoryRequest;

use App\Http\Resources\DoctorResource;

use App\Models\Api\Admin\Doctor;

class DoctorController extends APIBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $doctor = Doctor::with('categories')->find(\Auth::id());

        return $this->sendResponse(['doctor' => DoctorResource::make($doctor)]);
    }

    /**
     * Sync categories
     *
     * @param CategoryRequest $request
     *
     * @return JsonResponse
     */
    public function syncCategories(CategoryRequest $request): JsonResponse
    {
        \Auth::user()->categories()->sync($request->input('categories'));

        return $this->sendResponse([], 'Сохранено', JsonResponse::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param DoctorRequest $request
     * @param Doctor $doctor
     * @return JsonResponse
     */
    public function update(DoctorRequest $request, Doctor $doctor): JsonResponse
    {
        if (!$doctor->update($request->validated())) {
            return $this->sendError('', JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->sendResponse([], 'Данные обновлены');
    }
}
