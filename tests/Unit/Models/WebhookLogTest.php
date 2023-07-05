<?php

namespace OnrampLab\Webhooks\Tests\Unit\Models;

use OnrampLab\Webhooks\Models\Webhook;
use OnrampLab\Webhooks\Models\WebhookLog;
use OnrampLab\Webhooks\Tests\TestCase;

class WebhookLogTest extends TestCase
{
    /**
     * @test
     */
    public function relationship_should_work(): void
    {
        $webhook = Webhook::factory()->create();
        $webhookLog = WebhookLog::factory()->create(['webhook_id' => $webhook->id]);
        $this->assertEquals($webhook->id, $webhookLog->webhook->id);
    }
}
