<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\CreateStudentRequest;
use App\Http\Resources\TeacherStudentCreatedDataResource;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use App\Services\TeacherStudentService;
use Symfony\Component\HttpFoundation\Response;

class TeacherStudentController extends Controller
{
    public function __construct(
        private readonly TeacherStudentService $teacherStudentService,
    ) {
    }

    public function store(CreateStudentRequest $request)
    {
        /** @var User $actor */
        $actor = $request->user();

        $created = $this->teacherStudentService->createStudent(
            actor: $actor,
            fullName: (string) $request->validated('full_name'),
            email: $request->validated('email'),
        );

        return ApiResponse::success(
            data: TeacherStudentCreatedDataResource::make([
                'student' => [
                    'id' => $created['student']->id,
                    'full_name' => $created['student']->full_name,
                    'teacher_id' => $created['student']->teacher_id,
                    'user_id' => $created['student']->user_id,
                ],
                'user' => [
                    'id' => $created['user']->id,
                    'email' => $created['user']->email,
                    'role' => $created['user']->role->value,
                    'status' => $created['user']->status->value,
                ],
                'set_password_url' => $created['setPasswordUrl'],
            ]),
            code: 'TEACHER_STUDENT_CREATED',
            status: Response::HTTP_CREATED,
        );
    }
}
