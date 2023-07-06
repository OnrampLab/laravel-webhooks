<?php

namespace OnrampLab\Webhooks\Tests\Classes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Account extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected static function newFactory(): Factory
    {
        return AccountFactory::new();
    }
}
