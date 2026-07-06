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
        Schema::create('reclamations', function (Blueprint $table) {
            $table->id();
            $table->string('numero_unique', 20)->unique(); // ex: REC-20240001
            $table->foreignId('client_id')->constrained('clients');
            $table->foreignId('categorie_id')->constrained('categories_reclamation');
            $table->foreignId('priorite_id')->constrained('priorites');
            $table->foreignId('statut_id')->constrained('statuts');
            $table->foreignId('agent_id')->nullable()->constrained('utilisateurs'); // agent actuellement assigné
            $table->string('objet', 255);
            $table->text('description');
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('client_id', 'idx_rec_client');
            $table->index('statut_id', 'idx_rec_statut');
            $table->index('priorite_id', 'idx_rec_priorite');
            $table->index('agent_id', 'idx_rec_agent');
            $table->index('created_at', 'idx_rec_created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reclamations');
    }
};
