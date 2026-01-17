<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentDashboardDataResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'widgets' => (array) data_get($this->resource, 'widgets', []),
            'message' => (string) data_get($this->resource, 'message', ''),
        ];
    }
}
