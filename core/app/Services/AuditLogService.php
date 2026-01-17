<?php

namespace App\Services;

use App\Models\AuditLog;

class AuditLogService
{
    public function write(
        int $actorUserId,
        ?int $teacherId,
        string $action,
        ?string $targetType = null,
        ?int $targetId = null,
        ?array $metadata = null
    ): AuditLog {
        return AuditLog::query()->create([
            'actor_user_id' => $actorUserId,
            'teacher_id' => $teacherId,
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'metadata' => $metadata,
        ]);
    }
}
