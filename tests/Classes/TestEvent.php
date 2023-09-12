<?php

namespace OnrampLab\Webhooks\Tests\Classes;

use OnrampLab\Webhooks\Contracts\Webhookable as WebhookableContract;
use \OnrampLab\Webhooks\Concerns\Webhookable as WebhookableTrait;
use OnrampLab\Webhooks\ValueObjects\ExclusionCriterion;

class TestEvent implements WebhookableContract
{
    use WebhookableTrait;

    public Account $account;
    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    public function getWebhookContext(): ?Account
    {
        return $this->account;
    }

    public function getExclusionCriteria(): array
    {
        return [
            new ExclusionCriterion([
                'name' => 'events',
                'values' => ['test_event']
            ]),
        ];
    }
}
