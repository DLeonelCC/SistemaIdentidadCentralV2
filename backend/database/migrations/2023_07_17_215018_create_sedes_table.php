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
        #Posiblemente esto se borre sera reemplazado por tenants
        Schema::create('sedes', function (Blueprint $table) {
            $table->id(); // Clave local
            $table->string('codigo', 10)->unique()->nullable(); // Clave global
            $table->string('nombre', 191);
            $table->string('direccion', 191)->nullable();
            $table->string('telefono', 15)->nullable();
            $table->char('celular', 9)->nullable();
            $table->integer('cant_personal')->default(0);
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
        Schema::dropIfExists('sedes');
    }
};
