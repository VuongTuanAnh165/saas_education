<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateTeacherRequest;
use App\Http\Resources\AdminTeacherCreatedDataResource;
use App\Http\Resources\EmptyDataResource;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use App\Services\AdminTeacherService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminTeacherController extends Controller
{
    public function __construct(
        private readonly AdminTeacherService $adminTeacherService,
    ) {
    }

    public function store(CreateTeacherRequest $request)
    {
        /** @var User $actor */
        $actor = $request->user();

        $created = $this->adminTeacherService->createTeacher(
            actor: $actor,
            email: (string) $request->validated('email'),
            displayName: (string) $request->validated('display_name'),
        );

        return ApiResponse::success(
            data: AdminTeacherCreatedDataResource::make([
                'teacher' => [
                    'id' => $created['teacher']->id,
                    'display_name' => $created['teacher']->display_name,
                    'status' => $created['teacher']->status->value,
                    'user_id' => $created['teacher']->user_id,
                ],
                'user' => [
                    'id' => $created['user']->id,
                    'email' => $created['user']->email,
                    'role' => $created['user']->role->value,
                    'status' => $created['user']->status->value,
                ],
                'set_password_url' => $created['setPasswordUrl'],
            ]),
            code: 'ADMIN_TEACHER_CREATED',
            status: Response::HTTP_CREATED,
        );
    }

    public function lock(Request $request, int $teacherId)
    {
        /** @var User $actor */
        $actor = $request->user();

        $this->adminTeacherService->lockTeacher(actor: $actor, teacherId: $teacherId);

        return ApiResponse::success(
            data: EmptyDataResource::make(null),
            code: 'ADMIN_TEACHER_LOCKED',
            status: Response::HTTP_OK,
        );
    }

    public function unlock(Request $request, int $teacherId)
    {
        /** @var User $actor */
        $actor = $request->user();

        $this->adminTeacherService->unlockTeacher(actor: $actor, teacherId: $teacherId);

        return ApiResponse::success(
            data: EmptyDataResource::make(null),
            code: 'ADMIN_TEACHER_UNLOCKED',
            status: Response::HTTP_OK,
        );
    }
}
