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
            $table->id();
            $table->bigInteger('organizer_id')->unsigned(); 
            $table->string('title', 150)->nullable();
            $table->text('description')->nullable();
            $table->string('location', 150)->nullable();
            $table->dateTime('event_date')->nullable();
            $table->text('banner_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();

             // Contraintes de clé étrangère
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
