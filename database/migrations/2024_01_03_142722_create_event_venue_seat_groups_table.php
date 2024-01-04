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
        Schema::create('event_venue_seat_groups', function (Blueprint $table) {
            $table->id();
            $table->decimal('price');
            $table->foreignId('event_venue_id')->constrained('event_venue')->cascadeOnDelete();
            $table->foreignId('seat_group_id')->constrained('seat_groups')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_venue_seat_groups');
    }
};
