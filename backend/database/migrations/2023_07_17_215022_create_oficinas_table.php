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
        Schema::create('oficinas', function (Blueprint $table) {
            $table->id(); // Clave local
            $table->string('codigo', 10)->unique()->nullable(); // Clave global
            $table->string('siglas', 10)->nullable();
            $table->string('nombre', 255)->unique();
            $table->unsignedTinyInteger('ordinal')->nullable();
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
        Schema::dropIfExists('oficinas');
    }
};
