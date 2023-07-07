<?php

namespace OnrampLab\Webhooks;

use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use OnrampLab\Webhooks\Events\FinalWebhookCallFailedEvent;
use OnrampLab\Webhooks\Events\WebhookCallFailedEvent;
use OnrampLab\Webhooks\Events\WebhookCallSucceededEvent;
use OnrampLab\Webhooks\Models\Webhook;
use OnrampLab\Webhooks\Models\WebhookLog;

class CallWebhookJob  implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public ?array $body;
    public ?string $endpoint;
    public ?string $eventName;
    public ?string $errorType;
    public ?string $errorMessage;
    public ?Carbon $eventOccurredAt;
    public ?float $executionStartTime;
    public array $headers = [];
    public string $httpVerb;
    public array $payload = [];
    public ?string $receivedAt;
    public ?Response $response;
    public ?string $sentAt;
    public Webhook $webhook;

    public function __construct(?array $payload, Webhook $webhook, ?Carbon $eventOccurredAt)
    {
        $this->payload = $payload;
        $this->webhook = $webhook;
        $this->eventOccurredAt = $eventOccurredAt;
        $this->endpoint = $webhook->endpoint;
        $this->httpVerb = $webhook->http_verb;
    }

    public function handle()
    {
        $lastAttempt = $this->attempts() >= $this->getMaxAttempts();

        try {
            $this->response = $this->makeRequest();
            $this->receivedAt = now()->toDateTimeString();

            if ($this->shouldLogWebhook()) {
                $this->createWebhookLog();
            }

            if (! Str::startsWith($this->response->getStatusCode(), 2)) {
                throw new Exception('Webhook call failed');
            }

            $this->dispatchEvent(WebhookCallSucceededEvent::class);

            return;
        } catch (Exception $exception) {
            if ($exception instanceof RequestException) {
                $this->response = $exception->getResponse();
                $this->errorType = get_class($exception);
                $this->errorMessage = $exception->getMessage();
            }

            if ($exception instanceof ConnectException) {
                $this->errorType = get_class($exception);
                $this->errorMessage = $exception->getMessage();
            }

            if (! $lastAttempt) {
                $this->dispatchEvent(WebhookCallFailedEvent::class);
                $this->release($this->getRetryInterval());
            }

            $this->dispatchEvent(FinalWebhookCallFailedEvent::class);
            $this->shouldThrowExceptionOnFailure() ? $this->fail($exception) : $this->delete();
        }
    }

    protected function makeRequest(): Response
    {
        $client = $this->getClient();
        $body = $this->getBody();
        $this->sentAt = now()->toDateTimeString();
        $this->executionStartTime = microtime(true);
        return $client->request($this->httpVerb, $this->endpoint, array_merge([
            'timeout' => $this->getRequestTimeout(),
            'headers' => $this->getHeaders(),
        ], $body));
    }

    protected function createWebhookLog()
    {
        $log = new WebhookLog([
            'webhook_id' => $this->webhook->id,
            'event_occurred_at' => $this->eventOccurredAt->toDateTimeString(),
            'endpoint' => $this->endpoint,
            'request_body' => $this->body,
            'sent_at' => $this->sentAt,
            'received_at' => $this->receivedAt,
            'response' => $this->response,
            'status_code' => $this->response->getStatusCode(),
            'execution_time' => microtime(true) - $this->executionStartTime,
            'error_type' => $this->errorType,
            'error_message' => $this->errorMessage
        ]);

        $log->save();
    }

    protected function getBody(): array
    {
        return strtoupper($this->httpVerb) === 'GET'
            ? ['query' => $this->payload]
            : ['body' => json_encode($this->payload)];
    }

    protected function getClient(): Client
    {
        return app(Client::class);
    }

    private function dispatchEvent(string $eventClass)
    {
        event(new $eventClass(
            $this->endpoint,
            $this->httpVerb,
            $this->payload,
            $this->headers,
            $this->attempts(),
            $this->response,
            $this->errorType,
            $this->errorMessage,
        ));
    }
    private function getSignatureHeader(array $headers, array $payload, string $secret): array
    {
        $signer = app(config('laravel-webhooks.signer'));
        $signature = $signer->generateSignature($payload, $secret);
        $headers[$signer->signatureHeaderName()] = $signature;
        return $headers;
    }

    private function shouldLogWebhook(): bool
    {
        return config('laravel-webhooks.should_create_webhook_log');
    }

    private function getRequestTimeout(): int
    {
        return config('laravel-webhooks.timeout_in_seconds');
    }

    private function getHeaders(): array
    {
        $config = config('laravel-webhooks');
        $headers = array_merge($config['headers'], $this->webhook->headers ?? []);
        $secret = $this->webhook->secret;
        if (! empty($secret)) {
            $headers = $this->getSignatureHeader($headers, $this->payload, $secret);
        }
        $this->headers = $headers;
        return $headers;
    }

    private function getMaxAttempts(): int
    {
        return config('laravel-webhooks.max_attempts');
    }

    private function shouldThrowExceptionOnFailure(): bool
    {
        return config('laravel-webhooks.throw_exception_on_failure');
    }

    private function getRetryInterval(): int
    {
        return config('laravel-webhooks.retry_interval');
    }
}
