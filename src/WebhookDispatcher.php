<?php

namespace OnrampLab\Webhooks;

use Carbon\Carbon;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Collection;
use OnrampLab\Webhooks\Contracts\Webhookable;
use OnrampLab\Webhooks\Models\Webhook;

class WebhookDispatcher
{
    protected ?Carbon $eventOccurredAt;

    public function handle(Webhookable $event)
    {
        $payload = $event->getWebhookPayload();
        $webhooks = $this->getWebhooks($event);
        $this->eventOccurredAt = $event->getEventOccurredAt();

        foreach ($webhooks as $webhook) {
            // Check if the event should be excluded
            if (!$this->areExclusionCriteriaMatched($event, $webhook)) {
                $this->dispatchWebhook($payload, $webhook);
            }
        }
    }

    protected function getWebhooks(Webhookable $event): Collection
    {
        $context = $event->getWebhookContext();
        $query = Webhook::query();
        $query->where('enabled', true);

        if (is_null($context)) {
            $webhooks = $query->get();
        } else {
            $webhooks = $query
                ->where('contextable_type', $context->getMorphClass())
                ->where('contextable_id', $context->id)
                ->get();
        }
        return $webhooks;
    }

    protected function areExclusionCriteriaMatched(Webhookable $event, Webhook $webhook): bool
    {
        //to be implemented
        return  false;
    }

    protected function dispatchWebhook(array $payload, Webhook $webhook): PendingDispatch
    {
        $callWebhookJob = new CallWebhookJob($payload, $webhook, $this->eventOccurredAt);
        $config = config('laravel-webhooks');
        return dispatch($callWebhookJob)->onQueue($config['queue']);
    }
}


