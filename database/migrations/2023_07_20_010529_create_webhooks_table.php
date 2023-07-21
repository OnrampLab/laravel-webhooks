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
        Schema::create('webhooks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('endpoint');
            $table->string('http_verb')->default('POST');
            $table->boolean('enabled')->default(true);
            $table->json('exclusion_criteria')->nullable();
            $table->unsignedBigInteger('contextable_id')->nullable();
            $table->string('contextable_type')->nullable();
            $table->json('headers')->nullable();
            $table->string('secret')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webhooks');
    }
};
