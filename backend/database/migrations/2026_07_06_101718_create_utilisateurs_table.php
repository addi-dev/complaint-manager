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
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles');
            $table->string('nom', 100);
            $table->string('prenom', 100);
            $table->date('date_naissance');
            $table->string('numero_cin', 20);
            $table->string('email', 150)->unique();
            $table->string('mot_de_passe'); // hashed (bcrypt)
            $table->boolean('actif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utilisateurs');
    }
};
