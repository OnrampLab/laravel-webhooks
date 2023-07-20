<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('webhook_id');
            $table->timestamp('event_occurred_at');
            $table->string('endpoint');
            $table->json('request_body');
            $table->timestamp('sent_at');
            $table->timestamp('received_at')->nullable();
            $table->text('response')->nullable();
            $table->string('status_code')->nullable();
            $table->decimal('execution_time', 10, 4)->nullable();
            $table->text('error_type')->nullable();
            $table->text('error_message')->nullable();
            $table->foreign('webhook_id')->references('id')->on('webhooks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webhook_logs');
    }
};
