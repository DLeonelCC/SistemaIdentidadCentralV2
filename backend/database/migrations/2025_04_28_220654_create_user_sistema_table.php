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
        Schema::create('user_sistema', function (Blueprint $table) {
            $table->id();
            $table->boolean('active')->default(false);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('sistema_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('sistema_id')->references('id')->on('sistemas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sistema');
    }
};
