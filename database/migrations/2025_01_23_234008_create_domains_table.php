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
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->string('domain');
            $table->foreignId('tenant_app_id')->constrained();
            $table->foreignId('tenant_id')->constrained();
            $table->foreignId('application_id')->constrained();
            $table->tinyInteger('status')->comments("
                0: Pending
                1: Active
                2: Failed
            ")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
