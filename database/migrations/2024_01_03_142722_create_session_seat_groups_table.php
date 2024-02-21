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
        Schema::create('session_seat_groups', function (Blueprint $table) {
            $table->id();
            $table->decimal('price');
            $table->foreignId('session_id')->constrained('sessions')->cascadeOnDelete();
            $table->string('seat_group_id');
            $table->foreign('seat_group_id')->references('id')->on('seat_groups');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_seat_groups');
    }
};
