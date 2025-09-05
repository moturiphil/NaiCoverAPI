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
        Schema::create('policy_attributes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('policy_type_id')->constrained('policy_types')->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('label', 100);
            $table->enum('field_type', ['text', 'number', 'date', 'select']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policy_attributes');
    }
};
