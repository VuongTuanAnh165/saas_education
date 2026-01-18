<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthLoginDataResource extends JsonResource
{
    /**
     * Schema for AUTH_LOGIN_SUCCESS.
     *
     * This resource exists to provide a stable "data" schema for Scramble docs.
     */
    public function toArray(Request $request): array
    {
        return [
            'token' => (string) data_get($this->resource, 'token'),
            'user' => [
                'id' => (int) data_get($this->resource, 'user.id'),
                'email' => (string) data_get($this->resource, 'user.email'),
                'role' => (string) data_get($this->resource, 'user.role'),
                'status' => (string) data_get($this->resource, 'user.status'),
            ],
        ];
    }
}
