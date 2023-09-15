<?php

namespace OnrampLab\Webhooks\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OnrampLab\Webhooks\AttributeCastors\ExclusionCriteriaCastor;
use OnrampLab\Webhooks\Database\Factories\WebhookFactory;


class Webhook extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'endpoint',
        'http_verb',
        'enabled',
        'exclusion_criteria',
        'contextable_id',
        'contextable_type',
        'headers',
        'secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'enabled' => 'boolean',
        'exclusion_criteria' => ExclusionCriteriaCastor::class,
        'headers' => 'array'
    ];

    protected static function newFactory(): Factory
    {
        return WebhookFactory::new();
    }

    public function webhookLogs(): HasMany
    {
        return $this->hasMany(WebhookLog::class);
    }
}
