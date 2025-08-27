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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->char('ruc', 11);
            $table->string('razon_social', 191);
            $table->string('email', 191)->nullable();
            $table->string('logo', 191)->nullable();
            $table->integer('tot_sedes')->default(0);
            $table->integer('tot_oficinas')->default(0);
            $table->integer('tot_proyectos')->default(0);
            $table->integer('tot_users')->default(0);
            $table->integer('tot_personals')->default(0);
            $table->integer('tot_personas')->default(0);

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
        Schema::dropIfExists('empresas');
    }
};
