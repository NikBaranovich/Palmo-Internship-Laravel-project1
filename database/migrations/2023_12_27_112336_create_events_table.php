<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('poster_path')->nullable();
            $table->string('backdrop_path')->nullable();
            $table->text('overview')->nullable();
            $table->timestamp('release_date');
            $table->string('trailer_url')->nullable();
            $table->bigInteger('views_count')->default('0');
            $table->foreignId('event_type_id')->constrained('event_types')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Storage::disk('public')->deleteDirectory('events/');
        Schema::dropIfExists('events');
    }
};
