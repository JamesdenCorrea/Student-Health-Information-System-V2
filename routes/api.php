<?php

use App\Http\Controllers\Api\ClinicVisitController;
use App\Http\Controllers\Api\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/health', fn () => [
    'name' => config('app.name'),
    'status' => 'ok',
]);

Route::apiResource('students', StudentController::class);
Route::get('students/{student}/clinic-visits', [ClinicVisitController::class, 'index']);
Route::post('students/{student}/clinic-visits', [ClinicVisitController::class, 'store']);
Route::apiResource('clinic-visits', ClinicVisitController::class)
    ->except(['index', 'store'])
    ->parameters(['clinic-visits' => 'clinicVisit']);
