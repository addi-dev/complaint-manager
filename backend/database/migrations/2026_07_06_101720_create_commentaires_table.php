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
        Schema::create('commentaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reclamation_id')->constrained('reclamations');
            $table->foreignId('auteur_id')->nullable()->constrained('utilisateurs'); // NULL = commentaire client non connecté
            $table->foreignId('client_id')->nullable()->constrained('clients'); // renseigné si auteur est le client
            $table->text('contenu');
            $table->boolean('interne')->default(false);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commentaires');
    }
};
