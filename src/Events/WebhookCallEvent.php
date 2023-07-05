<?php

namespace OnrampLab\Webhooks\Events;

use GuzzleHttp\Psr7\Response;

class WebhookCallEvent
{
    public function __construct(
        public string $httpVerb,
        public string $endpoint,
        public array $payload,
        public array $headers,
        public int $attempt,
        public ?Response $response,
        public ?string $errorType,
        public ?string $errorMessage,
    ) {
    }
}
