<?php

namespace OnrampLab\Webhooks\Tests\Feature;

use OnrampLab\Webhooks\CallWebhookJob;
use OnrampLab\Webhooks\Models\Webhook;
use OnrampLab\Webhooks\Tests\Classes\Account;
use OnrampLab\Webhooks\Tests\Classes\TestEvent;
use OnrampLab\Webhooks\Tests\TestCase;
use OnrampLab\Webhooks\WebhookDispatcher;
use Illuminate\Support\Facades\Queue;


class WebhookDispatcherTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->account = Account::factory()->create();
        $attributes = [
            'contextable_id' => $this->account->id,
            'contextable_type' => get_class($this->account)
        ];
        $this->webhook = Webhook::factory()->create($attributes);
        $this->webhook2 = Webhook::factory()->create();
        $this->event = new TestEvent($this->account);
    }

    /**
     * @test
     */
    public function should_dispatch_webhook_according_to_context(): void
    {

        Queue::fake();

        $dispatcher = new WebhookDispatcher();
        $dispatcher->handle($this->event);

        Queue::assertPushed(CallWebhookJob::class, 1);
        Queue::assertPushed(CallWebhookJob::class, function ($job) {
            return $job->webhook->id === $this->webhook->id;
        });
    }
}
