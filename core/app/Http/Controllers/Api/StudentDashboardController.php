<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentDashboardDataResource;
use App\Http\Responses\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

class StudentDashboardController extends Controller
{
    public function __invoke()
    {
        return ApiResponse::success(
            data: StudentDashboardDataResource::make([
                'widgets' => [],
                'message' => __('api.student_dashboard_loaded'),
            ]),
            code: 'STUDENT_DASHBOARD_LOADED',
            status: Response::HTTP_OK,
        );
    }
}
