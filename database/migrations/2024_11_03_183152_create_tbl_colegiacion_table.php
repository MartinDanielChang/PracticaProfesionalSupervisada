<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('tbl_colegiacion', function (Blueprint $table) {
        $table->id('id_colegiacion');
        $table->string('preinscripcion', 50)->nullable(); 
        $table->string('empresaPractica', 100)->nullable(); 
        $table->string('labora', 2)->nullable(); 
        $table->string('unidadDepartamento', 100)->nullable(); 
        $table->string('direccionExacta', 255)->nullable();
        $table->string('celularEmpresa', 20)->nullable(); 
        $table->string('telefonoFijo', 20)->nullable(); 
        $table->string('extension', 10)->nullable(); 
        $table->string('correoEmpresa', 100)->nullable();
        $table->string('cargo', 100)->nullable(); 
        $table->date('fechaIngreso')->nullable(); 
        $table->string('estado_nota', 50)->nullable(); 
        $table->dateTime('fecha_ingreso')->nullable(); 
        $table->foreignId('user_id')
              ->constrained('users')
              ->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_colegiacion');
    }
};
