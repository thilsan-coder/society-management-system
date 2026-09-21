<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    public static function log(?User $user, string $action, ?string $modelType = null, ?int $modelId = null, ?array $changes = null): AuditLog
    {
        return AuditLog::create([
            'user_id' => $user?->id ?? auth()->id(),
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'changes_json' => $changes,
            'ip_address' => Request::ip(),
            'created_at' => now(),
        ]);
    }
}
