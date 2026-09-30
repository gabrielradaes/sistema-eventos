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
        Schema::table('events', function (Blueprint $table) {
            // Estado de la solicitud. Por defecto 'aprobado' para no romper los que ya creaste.
            $table->enum('status', ['pendiente', 'aprobado', 'rechazado'])->default('aprobado')->after('id');
            
            // Quién hizo la solicitud
            $table->foreignId('requester_id')->nullable()->constrained('users')->after('status');
            
            // Personal de apoyo solicitado
            $table->string('support_staff')->nullable()->after('instructor_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            //
        });
    }
};
