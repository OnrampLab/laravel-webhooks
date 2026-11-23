<?php

namespace OnrampLab\Webhooks\Tests\Unit;

use OnrampLab\Webhooks\CallWebhookJob;
use OnrampLab\Webhooks\Models\Webhook;
use OnrampLab\Webhooks\Tests\Classes\Account;
use OnrampLab\Webhooks\Tests\Classes\TestEvent;
use OnrampLab\Webhooks\Tests\TestCase;
use OnrampLab\Webhooks\WebhookDispatcher;
use Mockery\MockInterface;

class WebhookDispatcherTest extends TestCase
{
    protected Account $account;
    protected Webhook $webhook;
    protected TestEvent $event;
    protected MockInterface $callWebhookJobMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->account = Account::factory()->create();
        $attributes = [
            'contextable_id' => $this->account->id,
            'contextable_type' => get_class($this->account)
        ];
        $this->webhook = Webhook::factory()->create($attributes);
        $this->event = new TestEvent($this->account);
        $this->callWebhookJobMock = $this->mock(CallWebhookJob::class);
    }

    public function test_should_not_dispatch_webhook_when_exclusion_criteria_are_matched(): void
    {
        $dispatcher = new WebhookDispatcher();
        $dispatcher->handle($this->event);
        $this->callWebhookJobMock->shouldNotHaveReceived('dispatch');
    }
}

