<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminTeacherCreatedDataResource extends JsonResource
{
    /**
     * Schema for ADMIN_TEACHER_CREATED.
     *
     * This resource exists to provide a stable "data" schema for Scramble docs.
     */
    public function toArray(Request $request): array
    {
        return [
            'teacher' => [
                'id' => (int) data_get($this->resource, 'teacher.id'),
                'display_name' => (string) data_get($this->resource, 'teacher.display_name'),
                'status' => (string) data_get($this->resource, 'teacher.status'),
                'user_id' => (int) data_get($this->resource, 'teacher.user_id'),
            ],
            'user' => [
                'id' => (int) data_get($this->resource, 'user.id'),
                'email' => (string) data_get($this->resource, 'user.email'),
                'role' => (string) data_get($this->resource, 'user.role'),
                'status' => (string) data_get($this->resource, 'user.status'),
            ],
            'set_password_url' => (string) data_get($this->resource, 'set_password_url'),
        ];
    }
}
