<?php

namespace App\Services;

use App\Models\AuditLog;

class AuditLogService
{
    /**
     * Write an audit log entry.
     *
     * Sprint 0 note: audit log is a foundation feature to track important actions
     * (auth, admin operations). Keep metadata small and avoid sensitive data.
     */
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
