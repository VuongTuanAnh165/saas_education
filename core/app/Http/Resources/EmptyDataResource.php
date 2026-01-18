<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmptyDataResource extends JsonResource
{
    /**
     * Represents an empty payload.
     *
     * Used to enforce API contract: success responses MUST have data as an object.
     */
    public function toArray(Request $request): array
    {
        return [];
    }
}
