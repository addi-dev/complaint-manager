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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->nullable()->constrained('utilisateurs');
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->foreignId('reclamation_id')->nullable()->constrained('reclamations');
            $table->string('type', 50);
            $table->text('message');
            $table->boolean('lu')->default(false);
            $table->timestamp('created_at')->useCurrent();

            $table->index(['utilisateur_id', 'lu'], 'idx_notif_user_lu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
