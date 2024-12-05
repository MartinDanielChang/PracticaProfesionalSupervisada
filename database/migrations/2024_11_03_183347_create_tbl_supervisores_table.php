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
        Schema::create('tbl_supervisores', function (Blueprint $table) {
            $table->id('id_supervisor'); // Crea un campo id_supervisor INT AUTO_INCREMENT
            $table->string('nombre', 255); // Crea un campo nombre VARCHAR(255) NOT NULL
            $table->string('contacto', 255); // Crea un campo contacto VARCHAR(255) NOT NULL
            $table->integer('no_estudiantes')->default(0); // Crea un campo no_estudiantes INT NULL con valor predeterminado 0
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
        Schema::dropIfExists('tbl_supervisores');
    }
};
