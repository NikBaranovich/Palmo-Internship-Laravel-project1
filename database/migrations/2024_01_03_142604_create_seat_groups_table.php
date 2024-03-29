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
        Schema::create('seat_groups', function (Blueprint $table) {
            $table->string('id')->unique();
            $table->string('name')->nullable();
            $table->string('color')->nullable();
            $table->integer('number')->nullable();
            $table->foreignId('hall_id')->constrained('halls')->cascadeOnDelete();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seat_groups');
    }
};
