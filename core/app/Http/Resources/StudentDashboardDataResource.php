<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentDashboardDataResource extends JsonResource
{
    /**
     * Schema for STUDENT_DASHBOARD_LOADED.
     *
     * Sprint 0: dashboard is intentionally empty.
     * This resource exists to provide a stable "data" schema for Scramble docs.
     */
    public function toArray(Request $request): array
    {
        return [
            'widgets' => (array) data_get($this->resource, 'widgets', []),
            'message' => (string) data_get($this->resource, 'message', ''),
        ];
    }
}
