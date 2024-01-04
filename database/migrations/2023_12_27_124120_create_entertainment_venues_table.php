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
        Schema::create('entertainment_venues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('map_link')->nullable();
            $table->string('city');
            $table->string('street');
            $table->string('description')->nullable();
            $table->foreignId('venue_type_id')->constrained('venue_types')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entertainment_venues');
    }
};
