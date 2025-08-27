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
        Schema::create('personals', function (Blueprint $table) {
            $table->id();
            $table->integer('codigo')->unique()->nullable();
            $table->string('codigo_air', 6)->nullable();
            $table->date('fec_ini')->nullable();
            $table->date('fec_fin')->nullable();
            $table->enum('tipo_contrato', ['SERVIR 30057', 'NOMBRADO 276', 'CONTRATADO 276', 'PRIVADO 728', 'PRIVADO SC 728', 'CAS 1057', 'FAG'])->nullable()->default(null);

            $table->string('cond_g_oc')->nullable();
            $table->string('nivel_remunerativo')->nullable();
            $table->integer('tiempo_entidad')->nullable();
            $table->integer('quinquenio')->nullable();
            $table->string('rep_provincia')->nullable();
            $table->string('categoria_ocupacional')->nullable();

            $table->enum('sis_pen', ['AFP', 'ONP', 'NO AFIL.', 'RETIRO_95.5'])->nullable();
            $table->enum('tipo_afp', ['HÁBITAT', 'INTEGRA', 'PROFUTURO', 'PRIMA'])->nullable();
            $table->string('cuspp', 30)->nullable();
            $table->enum('tip_com_seg', ['MIXTO', 'FLUJO', 'NINGUNO'])->nullable();

            $table->boolean('discapacidad')->nullable();
            $table->boolean('sindicalizado')->nullable();

            $table->text('observacion')->nullable();
            $table->enum('condicion', ['PENDIENTE', 'ALTA', 'BAJA'])->default('PENDIENTE')->nullable();

            $table->string('persona_num_doc', 11)->nullable();

            $table->foreign('persona_num_doc')->references('num_doc')->on('personas')->onDelete('set null');
            $table->timestamps();

            //Uniqued Combined
            $table->unique(['persona_num_doc', 'tenant_id']);

            //TenantId
            $table->unsignedBigInteger('tenant_id')->default(1)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personals');
    }
};
