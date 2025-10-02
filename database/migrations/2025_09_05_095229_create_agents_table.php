<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('education_level_id')->constrained()->cascadeOnDelete()->nullable();
            $table->foreignId('experience_level_id')->constrained()->cascadeOnDelete()->nullable();
            $table->string('area_of_operation')->nullable();
            $table->text('experience')->nullable();
            // $table->boolean('police_clearance')->default(false);
            $table->string('police_clearance_path')->nullable();
            $table->string('cv_path')->nullable();
            $table->string('id_number')->unique();
            $table->string('id_path');
            $table->string('passport_photo_path');
            $table->string('diploma_certificate_path')->nullable();
            $table->string('degree_certificate_path')->nullable();
            $table->string('ira_certificate');
            $table->boolean('is_verified')->default(false);
            $table->string('verification_status')->default('pending');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
