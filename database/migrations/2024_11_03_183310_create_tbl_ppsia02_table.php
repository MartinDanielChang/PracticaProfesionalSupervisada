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
    Schema::create('tbl_ppsia02', function (Blueprint $table) {
        $table->id(); // Crea un campo id INT AUTO_INCREMENT
        $table->string('nombre_estudiante', 255); // Crea un campo nombre_estudiante VARCHAR(255) NOT NULL
        $table->string('email', 255)->unique(); // Crea un campo email VARCHAR(255) UNIQUE NOT NULL
        $table->string('telefono', 20)->nullable(); // Crea un campo telefono VARCHAR(20) NULL
        $table->date('fecha_inicio')->nullable(); // Crea un campo fecha_inicio DATE NULL
        $table->date('fecha_finalizacion')->nullable(); // Crea un campo fecha_fin DATE NULL
        $table->integer('horas_totales'); // Crea un campo horas_totales INT NOT NULL
        $table->string('estado', 50)->nullable(); // Crea un campo estado VARCHAR(50) NULL
        $table->string('observaciones')->nullable(); // Crea un campo observaciones TEXT NULL
        $table->foreignId('user_id') // Agrega una columna para la relación con la tabla users
              ->constrained('users') // Define la relación con la tabla users
              ->onDelete('cascade'); // Si el usuario se elimina, también se eliminan los registros de ppsia02
        
        // Nuevas columnas de acuerdo al formulario
        $table->time('hora_inicio_lunes_viernes1')->nullable(); // Hora inicio Lunes-Viernes 1
        $table->time('hora_fin_lunes_viernes1')->nullable(); // Hora fin Lunes-Viernes 1
        $table->time('hora_inicio_lunes_viernes2')->nullable(); // Hora inicio Lunes-Viernes 2
        $table->time('hora_fin_lunes_viernes2')->nullable(); // Hora fin Lunes-Viernes 2
        $table->time('hora_inicio_sabado')->nullable(); // Hora inicio Sábado
        $table->time('hora_fin_sabado')->nullable(); // Hora fin Sábado
        
        // Información del puesto de trabajo
        $table->string('puesto', 255); // Nombre del puesto
        $table->string('departamento', 255)->nullable(); // Departamento
        $table->date('fecha_ingreso_institucion'); // Fecha de ingreso a la institución
        $table->date('fecha_inicio_puesto'); // Fecha de inicio en el puesto actual
        $table->enum('jornada_laboral', ['Lunes a Viernes', 'Lunes a Sábado']); // Jornada laboral
        
        // Información de la institución
        $table->string('nombre_institucion', 255); // Nombre de la institución
        $table->text('direccion_institucion'); // Dirección de la institución
        $table->enum('tipo_institucion', ['Publica', 'Privada']); // Tipo de institución
        $table->date('fecha_constitucion')->nullable(); // Fecha de constitución de la institución
        
        // Información sobre el jefe inmediato
        $table->string('nombre_jefe', 255); // Nombre del jefe
        $table->string('cargo_jefe', 255); // Cargo del jefe
        $table->string('correo_jefe', 255); // Correo del jefe
        $table->string('telefono_jefe', 20)->nullable(); // Teléfono del jefe
        $table->string('celular_jefe', 20); // Celular del jefe
        $table->enum('nivel_academico', ['Licenciatura', 'Maestria', 'Doctorado']); // Nivel académico del jefe

         // Foreign key to tbl_solicitudes, explicitly reference 'id_solicitud'
         $table->foreignId('solicitud_id') 
         ->constrained('tbl_solicitudes', 'id_solicitud') // Explicitly reference 'id_solicitud' column
         ->onDelete('cascade'); 

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
        Schema::dropIfExists('tbl_ppsia02');
    }
};
