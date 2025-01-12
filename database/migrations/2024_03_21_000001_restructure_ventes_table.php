<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // D'abord, supprimez l'ancienne table
        Schema::dropIfExists('ventes');

        // Créez la nouvelle table avec la structure souhaitée
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->decimal('montant_total', 10, 2);
            $table->date('date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ventes');
    }
}; 