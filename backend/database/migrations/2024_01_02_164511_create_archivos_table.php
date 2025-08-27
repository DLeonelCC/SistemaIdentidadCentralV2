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
        Schema::create('archivos', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['PDF', 'IMAGEN']);
            $table->string('tipo_doc', 191)->nullable();
            $table->string('num_doc', 191)->nullable();
            $table->date('fecha')->nullable();
            $table->string('descripcion', 191)->nullable();
            $table->string('url_archivo', 255)->nullable();

            $table->unsignedBigInteger('contrato_id')->nullable();

            $table->timestamps();

            //TenantId
            $table->unsignedBigInteger('tenant_id')->default(1)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archivos');
    }
};
