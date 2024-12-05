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
        Schema::create('tbl_nota_aceptacion', function (Blueprint $table) {
            $table->id(); // Crea un campo id INT AUTO_INCREMENT
            $table->string('estado_nota', 50)->nullable(); // Crea un campo estado_nota VARCHAR(50) NULL
            $table->timestamp('fecha_ingreso')->nullable(); // Crea un campo fecha_ingreso DATETIME NULL
            $table->foreignId('user_id') // Agrega una columna para la relación con la tabla users
                  ->constrained('users') // Define la relación con la tabla users
                  ->onDelete('cascade'); // Si el usuario se elimina, también se elimina la nota de aceptación
    
            // Define explicitamente la clave foránea para la tabla tbl_solicitudes
            $table->unsignedBigInteger('solicitud_id'); // Crea una columna solicitud_id
            $table->foreign('solicitud_id') // Declara la relación foránea
                  ->references('id_solicitud') // Especifica la columna referenciada en tbl_solicitudes
                  ->on('tbl_solicitudes') // Tabla referenciada
                  ->onDelete('cascade'); // Si la solicitud se elimina, elimina la nota de aceptación
    
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
        Schema::dropIfExists('tbl_nota_aceptacion');
    }
};
