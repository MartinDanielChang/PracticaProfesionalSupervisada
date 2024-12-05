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
        Schema::create('tbl_proyectos', function (Blueprint $table) {
            $table->id(); // Crea un campo id INT AUTO_INCREMENT
            $table->string('nombre_proyecto', 255); // Crea un campo nombre_proyecto VARCHAR(255) NOT NULL
            $table->string('facultad', 255); // Crea un campo facultad VARCHAR(255) NOT NULL
            $table->string('departamento_academico', 255); // Crea un campo departamento_academico VARCHAR(255) NOT NULL
            $table->string('carrera', 255); // Crea un campo carrera VARCHAR(255) NOT NULL
            $table->enum('modalidad', ['Unidisciplinar', 'Multidisciplinar', 'Interdisciplinar', 'Transdisciplinar']); // Crea un campo modalidad ENUM NOT NULL
            $table->set('categorias_vinculacion', [
                'Educación No Formal', 
                'Educación Permanente para graduados (Programa Alumni)', 
                'Desarrollo Regional', 
                'Desarrollo Local', 
                'Investigación-acción-participación', 
                'Asesoría técnico-científica', 
                'Artísticos-culturales', 
                'Otras áreas'
            ]); // Crea un campo categorias_vinculacion SET NOT NULL
            $table->string('coordinador_nombre', 255); // Crea un campo coordinador_nombre VARCHAR(255) NOT NULL
            $table->string('coordinador_email', 255); // Crea un campo coordinador_email VARCHAR(255) NOT NULL
            $table->string('entidad_contraparte_nombre', 255)->nullable(); // Crea un campo entidad_contraparte_nombre VARCHAR(255) NULL
            $table->text('resumen_proyecto'); // Crea un campo resumen_proyecto TEXT NOT NULL
            $table->decimal('monto_total', 15, 2); // Crea un campo monto_total DECIMAL(15,2) NOT NULL
            $table->timestamps(); // Crea campos created_at y updated_at
            $table->timestamp('fecha_registro')->nullable()->default(DB::raw('CURRENT_TIMESTAMP')); // Crea un campo fecha_registro TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_proyectos');
    }
};
