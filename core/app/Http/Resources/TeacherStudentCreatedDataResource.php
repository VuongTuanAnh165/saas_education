<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherStudentCreatedDataResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'student' => [
                'id' => (int) data_get($this->resource, 'student.id'),
                'full_name' => (string) data_get($this->resource, 'student.full_name'),
                'teacher_id' => (int) data_get($this->resource, 'student.teacher_id'),
                'user_id' => (int) data_get($this->resource, 'student.user_id'),
            ],
            'user' => [
                'id' => (int) data_get($this->resource, 'user.id'),
                'email' => (string) data_get($this->resource, 'user.email'),
                'role' => (string) data_get($this->resource, 'user.role'),
                'status' => (string) data_get($this->resource, 'user.status'),
            ],
            'set_password_url' => data_get($this->resource, 'set_password_url'),
        ];
    }
}
