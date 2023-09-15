<?php

namespace OnrampLab\Webhooks\Concerns;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OnrampLab\Webhooks\Models\Webhook;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @mixin Model
 */
trait Webhookable
{
    public function getEventOccurredAt(): ?Carbon
    {
        return now();
    }

    public function getWebhookContext(): ?Model
    {
        return null;
    }

    public function getWebhookPayload(): array
    {
        return [];
    }

    public function getExclusionCriteria(): array
    {
        return [];
    }
}
