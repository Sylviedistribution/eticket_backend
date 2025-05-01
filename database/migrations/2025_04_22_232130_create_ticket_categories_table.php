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
        Schema::create('ticket_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
        
            $table->string('name', 50); // Ex : "Standard", "VIP", "Premium"
            $table->decimal('price', 8, 2); // Prix fixé par l'organisateur
            $table->integer('quantity'); // Nombre de tickets dispo pour cette catégorie
            $table->string('currency', 5)->default('XOF');
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_categories');
    }
};
