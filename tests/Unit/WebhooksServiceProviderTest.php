<?php

namespace OnrampLab\Webhooks\Tests\Unit;

use Illuminate\Support\Facades\Event;
use OnrampLab\Webhooks\Contracts\Webhookable;
use OnrampLab\Webhooks\Tests\TestCase;

class WebhooksServiceProviderTest extends TestCase
{
    public function test_service_provider_registers_listener_for_webhookable_events(): void
    {
        $this->assertTrue(Event::hasListeners(Webhookable::class));
    }
}
