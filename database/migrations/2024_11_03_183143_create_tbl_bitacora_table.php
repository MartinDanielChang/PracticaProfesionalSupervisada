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
        Schema::create('tbl_bitacora', function (Blueprint $table) {
            $table->id('id_bitacora'); // Crea un campo id_bitacora INT AUTO_INCREMENT
            $table->string('accion', 100); // Crea un campo accion VARCHAR(100)
            $table->dateTime('fecha'); // Crea un campo fecha DATETIME
            $table->string('descripcion', 100); // Crea un campo descripcion VARCHAR(100)
            $table->timestamps(); // Crea campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_bitacora');
    }
};
