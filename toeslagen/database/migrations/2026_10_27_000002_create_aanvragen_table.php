<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aanvragen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('toeslag_id')->constrained('toeslagen')->cascadeOnDelete();
            $table->decimal('inkomen', 10, 2);                 // jaarinkomen
            $table->unsignedTinyInteger('huishouden');         // aantal personen
            $table->decimal('huur_per_maand', 8, 2)->nullable();        // alleen huurtoeslag
            $table->unsignedTinyInteger('aantal_kinderen')->nullable(); // alleen kinderopvangtoeslag
            $table->unsignedSmallInteger('opvang_uren')->nullable();    // alleen kinderopvangtoeslag
            $table->boolean('zorgverzekering')->nullable();             // alleen zorgtoeslag
            $table->string('status')->default('in_behandeling'); // in_behandeling, goedgekeurd, afgewezen
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aanvragen');
    }
};
