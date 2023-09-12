<?php

namespace OnrampLab\Webhooks\Contracts;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

interface Webhookable
{
    public function getWebhookPayload(): array;

    public function getWebhookContext(): ?Model;

    public function getEventOccurredAt(): ?Carbon;

    public function getExclusionCriteria(): array;
}
