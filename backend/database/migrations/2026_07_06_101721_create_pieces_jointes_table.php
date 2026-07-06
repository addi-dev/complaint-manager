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
        Schema::create('pieces_jointes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reclamation_id')->constrained('reclamations')->cascadeOnDelete();
            $table->string('nom_fichier', 255);
            $table->string('chemin', 500);
            $table->string('type_mime', 100)->nullable();
            $table->integer('taille')->unsigned()->nullable(); // taille en octets
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pieces_jointes');
    }
};
