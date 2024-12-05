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
    Schema::create('tbl_ppsia01', function (Blueprint $table) {
        $table->id(); // Creates an auto-incrementing id for the table
        $table->string('carrera', 255); 
        $table->string('practica', 255); 
        $table->string('dias', 255); 
        $table->string('horario', 255)->nullable(); 
        $table->date('fecha_inicio')->nullable(); 
        $table->date('fecha_finalizacion')->nullable(); 
        $table->string('modalidad', 255); 
        $table->string('correo_vinculacion', 255); 
        $table->string('actividad1', 255); 
        $table->string('actividad2', 255); 
        $table->string('actividad3', 255);
        $table->string('actividad4', 255); 
        $table->string('actividad5', 255); 
        $table->integer('total_horas'); 
        $table->string('ciudad', 255)->nullable(); 
        $table->date('fecha_emision')->nullable(); 
        $table->string('jefe', 255)->nullable(); 
        $table->string('celular', 20)->nullable(); 
        $table->string('estado_nota', 50)->nullable(); 
        $table->timestamp('fecha_ingreso')->nullable(); 
        
        // Foreign key to users table
        $table->foreignId('user_id') 
              ->constrained('users') 
              ->onDelete('cascade'); 
        
        // Foreign key to tbl_solicitudes, explicitly reference 'id_solicitud'
        $table->foreignId('solicitud_id') 
              ->constrained('tbl_solicitudes', 'id_solicitud') // Explicitly reference 'id_solicitud' column
              ->onDelete('cascade'); 
        
        $table->timestamps(); // Create created_at and updated_at columns
    });
}

    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_ppsia01');
    }
};
