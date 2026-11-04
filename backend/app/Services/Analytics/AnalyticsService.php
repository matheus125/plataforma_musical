<?php

namespace App\Services\Analytics;

use App\Models\AnalyticsEvent;

class AnalyticsService
{
    public function track(array $payload): AnalyticsEvent
    {
        return AnalyticsEvent::create([
            'user_id' => $payload['user_id'] ?? null,
            'session_id' => $payload['session_id'] ?? null,
            'event_name' => $payload['event_name'],
            'entity_type' => $payload['entity_type'] ?? null,
            'entity_id' => $payload['entity_id'] ?? null,
            'metadata' => $payload['metadata'] ?? null,
            'ip_hash' => $payload['ip_hash'] ?? null,
            'user_agent' => $payload['user_agent'] ?? null,
            'country' => $payload['country'] ?? null,
            'locale' => $payload['locale'] ?? null,
            'created_at' => $payload['created_at'] ?? now(),
        ]);
    }
}
