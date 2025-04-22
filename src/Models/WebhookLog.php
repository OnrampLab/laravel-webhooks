<?php

namespace OnrampLab\Webhooks\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OnrampLab\Webhooks\Database\Factories\WebhookLogFactory;

class WebhookLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'webhook_id',
        'event_occurred_at',
        'endpoint',
        'request_body',
        'sent_at',
        'received_at',
        'response',
        'status_code',
        'execution_time',
        'error_type',
        'error_message'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'request_body' => 'array',
        'sent_at' => 'datetime:H:i:s',
        'received_at' => 'datetime:H:i:s',
        'event_occurred_at' => 'datetime:H:i:s',
        'response' => 'array',
    ];


    protected static function newFactory(): Factory
    {
        return WebhookLogFactory::new();
    }

    public function webhook(): BelongsTo
    {
        return $this->belongsTo(Webhook::class);
    }
}
