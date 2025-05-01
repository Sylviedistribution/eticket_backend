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
        Schema::create('events', function (Blueprint $table) {
            $table->id(); // équivalent à bigIncrements
            $table->unsignedBigInteger('organizer_id');
            $table->string('title', 150)->nullable();
            $table->text('description')->nullable();
            $table->string('location', 150)->nullable();
            $table->date('event_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->text('banner_url')->nullable();
            $table->boolean('is_active')->default(1);
            $table->string('organizer_name', 80)->nullable();
            $table->unsignedInteger('capacity')->nullable(); // Non signé
            $table->enum('category', [
                'concert', 'théâtre', 'cinéma', 'conférence', 'formation', 'exposition',
                'festival', 'atelier', 'compétition', 'networking', 'webinaire', 'religieux',
                'sportif', 'culturel', 'jeux-video', 'autre'
            ])->nullable();
        
            $table->timestamps(); // gère automatiquement created_at et updated_at
        
            // Clé étrangère
            $table->foreign('organizer_id')->references('id')->on('users')->onDelete('cascade');
        });
        
          
    }        

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
