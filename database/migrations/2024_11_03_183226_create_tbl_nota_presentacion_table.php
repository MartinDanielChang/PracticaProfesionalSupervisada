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
        Schema::create('tbl_nota_presentacion', function (Blueprint $table) {
            $table->id(); // Create the id column for tbl_nota_presentacion
            $table->string('estado_nota', 50)->nullable(); // Create estado_nota column
            $table->timestamp('fecha_ingreso')->nullable(); // Create fecha_ingreso column
            $table->foreignId('user_id') // Foreign key for users table
                  ->constrained('users')
                  ->onDelete('cascade'); 
            $table->foreignId('solicitud_id') // Foreign key for tbl_solicitudes
                  ->constrained('tbl_solicitudes', 'id_solicitud') // Explicitly reference 'id_solicitud' instead of 'id'
                  ->onDelete('cascade'); // Cascade on delete
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
        Schema::dropIfExists('tbl_nota_presentacion');
    }
};
