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
        Schema::create('entertainment_venue_event_seat_groups', function (Blueprint $table) {
            $table->id();
            $table->decimal('price');
            $table->unsignedBigInteger('entertainment_venue_event_id');
            $table->foreign('entertainment_venue_event_id', 'fk_entertainment_venue_event_id')->references('id')->on('entertainment_venue_event')->cascadeOnDelete();
            $table->unsignedBigInteger('seat_group_id');
            $table->foreign('seat_group_id', 'fk_seat_group_id')->references('id')->on('seat_groups')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entertainment_venue_event_seat_groups');
    }
};
