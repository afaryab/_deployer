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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('domain')->unique();
            $table->integer('port')->length(4)->unique();
            $table->string('download_path');
            $table->string('folder_path');
            $table->string('public_path');
            $table->string('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('logo')->nullable();
            $table->string('provider');
            $table->json('meta')->nullable();
            $table->tinyInteger('status')->comments("
                0: Pending
                1: Active
                2: Failed
            ")->default(0);
            $table->string('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
