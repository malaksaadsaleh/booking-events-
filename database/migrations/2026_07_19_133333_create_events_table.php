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
            $table->text('describtion')->nullable();
            $table->string('title');
            $table->string('address')->nullable(); //we can use json if data is lat and long
            $table->date('start_date');
            $table->int('availiable_seats');
            $table->foreignId('category_id')->constrained('categories')->onUpdate('cascade')->onDelete('cascade'); 
            
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
