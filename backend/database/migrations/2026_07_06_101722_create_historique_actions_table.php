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
        Schema::create('historique_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reclamation_id')->constrained('reclamations');
            $table->foreignId('utilisateur_id')->nullable()->constrained('utilisateurs');
            $table->foreignId('ancien_statut_id')->nullable()->constrained('statuts');
            $table->foreignId('nouveau_statut_id')->nullable()->constrained('statuts');
            $table->string('action', 255); // description courte de l'action
            $table->text('details')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['reclamation_id', 'created_at'], 'idx_hist_rec');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historique_actions');
    }
};
