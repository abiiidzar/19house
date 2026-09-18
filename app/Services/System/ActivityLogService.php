<?php

namespace App\Services\System;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActivityLogService
{
    public function log(string $action, Model $subject, array $oldValues = [], array $newValues = [], ?User $actor = null, ?string $description = null): ActivityLog
    {
        $label = $subject->name ?? $subject->sku ?? $subject->order_number ?? $subject->code ?? $subject->id;

        return ActivityLog::create([
            'user_id' => $actor?->id ?? auth()->id(),
            'action' => $action,
            'subject_type' => $subject->getMorphClass(),
            'subject_id' => $subject->getKey(),
            'description' => $description ?? str_replace(['.', '_'], ' ', $action).' on '.class_basename($subject).' ('.$label.')',
            'old_values' => $oldValues ?: null,
            'new_values' => $newValues ?: null,
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }
}
