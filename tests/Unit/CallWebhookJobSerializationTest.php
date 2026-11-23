<?php

namespace OnrampLab\Webhooks\Tests\Unit;

use OnrampLab\Webhooks\CallWebhookJob;
use OnrampLab\Webhooks\Models\Webhook;
use OnrampLab\Webhooks\Tests\TestCase;

class CallWebhookJobSerializationTest extends TestCase
{
    public function test_call_webhook_job_survives_queue_serialization(): void
    {
        $webhook = Webhook::factory()->create();
        $job = new CallWebhookJob(['foo' => 'bar'], $webhook, now());

        $restored = unserialize(serialize($job));

        $this->assertSame($webhook->id, $restored->webhook->id);
        $this->assertSame($webhook->endpoint, $restored->endpoint);
        $this->assertSame($webhook->http_verb, $restored->httpVerb);
        $this->assertSame(['foo' => 'bar'], $restored->payload);
    }
}
