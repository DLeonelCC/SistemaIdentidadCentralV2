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
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();
            $table->string('num_doc_resolucion')->nullable();
            $table->enum('tipo', ['PROYECTO', 'OBRA']);
            $table->string('meta', 500)->nullable();
            $table->string('meta_codigo', 30)->nullable(); // CODIGO
            $table->string('codigo', 255)->nullable();
            $table->string('cui', 30)->nullable(); // CODIGO
            $table->string('nombre', 500);
            $table->string('obra', 500)->nullable();

            $table->string('pliego', 255)->default('458 GOBIERNO REGIONAL PUNO');
            $table->string('programa_presupuestal', 255)->nullable();
            $table->enum('modalidad_ejecucion', ['ADMINISTRACION DIRECTA', 'CONTRATA'])->nullable();
            $table->string('fte_fto', 255)->nullable();

            $table->enum('estado', ['PENDIENTE', 'PARALIZADO', 'EN EJECUCION', 'CULMINADO'])->default('PENDIENTE');
            $table->integer('cant_personal')->default(0);

            $table->string('escala')->nullable(); // ESCALA NUEVA - ANTIGUA
            $table->enum('tipo_categoria', ['A', 'B', 'C', 'E'])->nullable();
            $table->enum('tipo_acceso', ['ACCESO FACIL', 'ACCESO NORMAL', 'ACCESO INTERMEDIO', 'ACCESO DIFICIL'])->nullable();
            
            $table->char('charaux1', 30)->nullable()->comment('char auxiliar');
            $table->char('charaux2', 30)->nullable()->comment('char auxiliar');
            $table->char('charaux3', 30)->nullable()->comment('char auxiliar');
            $table->char('charaux4', 30)->nullable()->comment('char auxiliar');
            $table->char('charaux5', 30)->nullable()->comment('char auxiliar');
            $table->char('charaux6', 30)->nullable()->comment('char auxiliar');
            $table->char('charaux7', 30)->nullable()->comment('char auxiliar');
            $table->char('charaux8', 30)->nullable()->comment('char auxiliar');
            $table->char('charaux9', 30)->nullable()->comment('char auxiliar');
            $table->char('charaux10', 30)->nullable()->comment('char auxiliar');

            $table->unsignedBigInteger('oficina_id')->nullable();
            $table->char('ubigeo_codigo', 6)->nullable();

            $table->foreign('oficina_id')->references('id')->on('oficinas')->onDelete('set null');
            $table->foreign('ubigeo_codigo')->references('codigo')->on('ubigeos')->nullOnDelete();
            $table->timestamps();

            //Uniqued Combined
            $table->unique(['codigo', 'tenant_id']);

            //TenantId
            $table->unsignedBigInteger('tenant_id')->default(1)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};
