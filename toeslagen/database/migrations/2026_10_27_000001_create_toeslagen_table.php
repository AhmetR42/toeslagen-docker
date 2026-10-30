<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('toeslagen', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();          // huurtoeslag, zorgtoeslag, kinderopvangtoeslag
            $table->string('naam');
            $table->string('omschrijving');
            $table->json('voorwaarden');               // lijst met voorwaarden
            $table->decimal('max_inkomen', 10, 2);     // inkomensgrens per jaar
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('toeslagen');
    }
};
