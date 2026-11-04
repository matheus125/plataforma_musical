<?php

namespace App\Services\Billing;

use App\Models\Entitlement;
use App\Models\User;

class EntitlementService
{
    public function grant(User $user, string $key, ?int $value = null, mixed $expiresAt = null): Entitlement
    {
        return Entitlement::updateOrCreate(
            [
                'user_id' => $user->id,
                'key' => $key,
            ],
            [
                'value' => $value,
                'expires_at' => $expiresAt,
            ],
        );
    }

    public function has(User $user, string $key): bool
    {
        return Entitlement::query()
            ->where('user_id', $user->id)
            ->where('key', $key)
            ->where(function ($query) {
                $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->exists();
    }
}
