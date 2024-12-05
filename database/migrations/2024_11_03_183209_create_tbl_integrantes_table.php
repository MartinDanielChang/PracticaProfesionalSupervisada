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
        Schema::create('tbl_integrantes', function (Blueprint $table) {
            $table->id('id_integrante'); // Crea un campo id_integrante INT AUTO_INCREMENT
            $table->string('nombre', 255)->nullable(); // Crea un campo nombre VARCHAR(255) NULL
            $table->string('correo_electronico', 255)->nullable(); // Crea un campo correo_electronico VARCHAR(255) NULL
            $table->string('no_empleado', 50)->nullable(); // Crea un campo no_empleado VARCHAR(50) NULL
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
        Schema::dropIfExists('tbl_integrantes');
    }
};
