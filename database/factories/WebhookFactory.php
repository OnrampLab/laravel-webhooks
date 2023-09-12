<?php

namespace OnrampLab\Webhooks\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use OnrampLab\Webhooks\Models\Webhook;
use OnrampLab\Webhooks\ValueObjects\ExclusionCriterion;

class WebhookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string|null
     */
    protected $model = Webhook::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'endpoint' => $this->faker->url,
            'http_verb' => 'POST',
            'enabled' => true,
            'exclusion_criteria' => [
                new ExclusionCriterion([
                    'name' => 'account_ids',
                    'values' => [1, 2]
                ]),
                new ExclusionCriterion([
                    'name' => 'events',
                    'values' => ['test_event']
                ])
            ],
            'contextable_id' => $this->faker->randomNumber(),
            'contextable_type' => $this->faker->word(),
            'headers' => null,
            'secret' => null,
        ];
    }
}
