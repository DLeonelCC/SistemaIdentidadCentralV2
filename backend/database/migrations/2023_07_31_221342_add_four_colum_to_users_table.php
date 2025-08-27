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
        Schema::table('users', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('password');

            $table->unsignedBigInteger('cargo_id')->nullable();
            $table->unsignedBigInteger('oficina_id')->nullable();
            $table->string('persona_num_doc', 11)->nullable();

            $table->foreign('cargo_id')->references('id')->on('cargos')->onDelete('set null');
            $table->foreign('oficina_id')->references('id')->on('oficinas')->onDelete('set null');
            $table->foreign('persona_num_doc')->references('num_doc')->on('personas')->onDelete('set null');

            $table->unique('persona_num_doc', 'tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('photo');
            
            $table->dropForeign('users_cargo_id_foreign');
            $table->dropForeign('users_oficina_id_foreign');
            $table->dropForeign('users_persona_num_doc_foreign');

            $table->dropColumn('cargo_id');
            $table->dropColumn('oficina_id');
            $table->dropColumn('persona_num_doc');
        });
    }
};
