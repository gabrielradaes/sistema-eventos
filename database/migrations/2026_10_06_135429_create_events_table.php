<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function schemaUp(): void
{
    Schema::create('events', function (Blueprint $table) {
        $table->id();
        $table->string('title'); // Nombre del evento / Descripción
        $table->dateTime('start_time');
        $table->dateTime('end_time');
        $table->string('user_name')->nullable();
        $table->string('user_phone')->nullable();
        $table->string('authority_name')->nullable(); // Nombre de autoridad / Autoridad solicitante
        $table->string('responsible')->nullable(); // Responsable del evento
        $table->string('salon'); // Salón seleccionado
        $table->string('event_type')->nullable(); // Tipo de evento (Reconocimientos, etc.)
        $table->json('requirements')->nullable(); // Checkboxes (Sonido, Refrigerio, etc.)
        $table->integer('capacity')->nullable(); // Aforo
        $table->string('entry_type')->nullable(); // Tipo de ingreso (Libre, etc.)
        $table->string('external_coordinator_name')->nullable();
        $table->string('external_coordinator_phone')->nullable();
        $table->text('special_requirements')->nullable();
        $table->string('registered_by')->nullable();
        $table->string('internal_coordinator')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
