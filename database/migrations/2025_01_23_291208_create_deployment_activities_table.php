<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deployment_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained();
            $table->foreignId('application_id')->nullable()->constrained();
            $table->foreignId('tenant_app_id')->nullable()->constrained();;
            $table->foreignId('domain_id')->nullable()->constrained();
            $table->string('provider');
            $table->string('status');
            $table->integer('next_retry_id')->nullable();
            $table->string('method');
            $table->text('message')->nullable();
            $table->text('output')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deployment_activities');
    }
};
