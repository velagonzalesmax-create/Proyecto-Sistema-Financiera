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
    Schema::create('pagos_recurrentes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('categoria_id')->constrained()->onDelete('cascade');
        $table->string('nombre'); // Ej: "Pago de Luz", "Suscripción Netflix"
        $table->decimal('monto', 10, 2);
        $table->enum('frecuencia', ['semanal', 'mensual', 'anual'])->default('mensual');
        $table->date('fecha_vencimiento'); // Próxima fecha de cobro
        $table->integer('dias_preaviso')->default(3); // Días antes para notificar
        $table->enum('estado', ['activo', 'pausado', 'finalizado'])->default('activo');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos_recurrentes');
    }
};
