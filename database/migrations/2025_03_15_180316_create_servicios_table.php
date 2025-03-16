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
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('id_socio')->constrained('usuarios')->onDelete('cascade');
            $table->text('descripcion');
            $table->enum('tipo_plan', ['Mañana', 'Tarde', 'Día completo']);
            $table->enum('estado', ['Pendiente', 'En curso', 'Finalizado', 'Cancelado']);
            $table->text('direccion_servicio');
            $table->timestamp('fecha_solicitud')->useCurrent();
            $table->timestamp('fecha_finalizacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
