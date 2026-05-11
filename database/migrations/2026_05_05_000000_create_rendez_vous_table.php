<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rendez_vous', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('contact');
            $table->string('lieu_habitation')->nullable();
            $table->string('motif');
            $table->string('pasteur');
            $table->date('date_souhaitee');
            $table->time('heure_souhaitee')->nullable();
            $table->text('message')->nullable();
            $table->enum('statut', ['en_attente', 'confirme', 'termine', 'annule'])->default('en_attente');
            $table->text('notes_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rendez_vous');
    }
};
