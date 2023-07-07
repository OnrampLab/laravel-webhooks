<?php

namespace OnrampLab\Webhooks\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use OnrampLab\Webhooks\Models\Webhook;
use OnrampLab\Webhooks\Models\WebhookLog;

class WebhookLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string|null
     */
    protected $model = WebhookLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'webhook_id' => Webhook::factory(),
            'event_occurred_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'endpoint' => $this->faker->url,
            'request_body' => ['key1' => 'value1', 'key2' => 'value2'], // Example values for the JSON field
            'sent_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'received_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'response' => ['key1' => 'value1', 'key2' => 'value2'],
            'status_code' => $this->faker->randomElement([200, 400, 500]), // Nullable field
            'execution_time' => $this->faker->randomFloat(4, 0, 1000), // Nullable field
            'error_type' => '',
            'error_message' => ''
        ];
    }
}
