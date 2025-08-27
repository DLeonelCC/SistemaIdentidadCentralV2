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
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_doc', ['DNI', 'RUC', 'PASAPORTE']);
            $table->string('num_doc', 11)->unique();
            $table->string('nombre_completo', 573)->nullable();
            $table->string('nombres', 191)->nullable();
            $table->string('ap_paterno', 191)->nullable();
            $table->string('ap_materno', 191)->nullable();
            $table->string('email', 191)->nullable();
            $table->char('direccion', 191)->nullable();
            $table->string('celular', 9)->nullable();
            $table->date('fec_nacimiento')->nullable();
            $table->enum('sexo', ['Masculino', 'Femenino'])->nullable();
            $table->string('num_cuenta', 11)->unique()->nullable();
            $table->string('nacionalidad', 50)->nullable();
            $table->enum('estado_civil', ['Soltero', 'Casado', 'Divorciado', 'Separado', 'Viudo'])->nullable();
            $table->string('ruc', 11)->unique()->nullable();
            $table->string('pais', 50)->nullable();
            $table->boolean('datos_completos')->default(false);
            $table->char('ubigeo_actual', 6)->nullable();
            $table->char('ubigeo_nacimiento', 6)->nullable();
            $table->foreign('ubigeo_actual')->references('codigo')->on('ubigeos')->nullOnDelete();
            $table->foreign('ubigeo_nacimiento')->references('codigo')->on('ubigeos')->nullOnDelete();
            $table->timestamps();

            //Uniqued Combined
            $table->unique(['num_doc', 'tenant_id']);

            //TenantId
            $table->unsignedBigInteger('tenant_id')->default(1)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
