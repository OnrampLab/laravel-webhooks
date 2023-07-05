<?php

namespace OnrampLab\Webhooks\Tests\Classes;

use Illuminate\Database\Eloquent\Model;
use OnrampLab\Webhooks\Contracts\Webhookable as WebhookableContract;
use \OnrampLab\Webhooks\Concerns\Webhookable as WebhookableTrait;

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
}
