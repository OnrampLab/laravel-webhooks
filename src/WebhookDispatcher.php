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
        $webhooks = $this->getWebhooks($event);

        if ($webhooks->isEmpty()) {
            return;
        }

        $payload = $event->getWebhookPayload();
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
        $webhookExclusionCriteria = $webhook->exclusion_criteria;
        $eventExclusionCriteria = $event->getExclusionCriteria();

        if (is_null($webhookExclusionCriteria) || empty($eventExclusionCriteria))  {
            return false;
        }

        foreach ($eventExclusionCriteria as $eventExclusionCriterion) {
            $eventExclusionCriteriaName = $eventExclusionCriterion->getName();
            $eventExclusionCriteriaValues = $eventExclusionCriterion->getValues();

            foreach ($webhookExclusionCriteria as $webhookExclusionCriterion) {
                $webhookExclusionCriteriaName = $webhookExclusionCriterion->getName();
                $webhookExclusionCriteriaValues = $webhookExclusionCriterion->getValues();
                if ($eventExclusionCriteriaName === $webhookExclusionCriteriaName && count(array_intersect($webhookExclusionCriteriaValues, $eventExclusionCriteriaValues)) > 0) {
                    return true;
                }
            }
        }

        return  false;
    }

    protected function dispatchWebhook(array $payload, Webhook $webhook): PendingDispatch
    {
        $callWebhookJob = new CallWebhookJob($payload, $webhook, $this->eventOccurredAt);
        $config = config('laravel-webhooks');
        return dispatch($callWebhookJob)->onQueue($config['queue']);
    }
}


