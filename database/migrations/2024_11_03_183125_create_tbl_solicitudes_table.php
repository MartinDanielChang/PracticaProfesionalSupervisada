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
        Schema::create('tbl_solicitudes', function (Blueprint $table) {
            $table->id('id_solicitud'); // Creates 'id_solicitud' as the primary key (auto-incrementing)
            $table->foreignId('user_id') // Creates 'user_id' as a foreign key
                  ->constrained('users') // Ensures that 'user_id' references 'id' in 'users' table
                  ->onDelete('cascade'); // Deletes associated solicitudes when the user is deleted
            $table->text('estado_solicitud'); // 'estado_solicitud' field
            $table->dateTime('fecha_solicitud')->nullable(); // 'fecha_solicitud' field, nullable
            $table->text('descripcion')->nullable(); // 'descripcion' field, nullable
            $table->string('tipo_practica', 255)->nullable(); // 'tipo_practica' field, nullable
            $table->timestamps(); // 'created_at' and 'updated_at' timestamps
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_solicitudes');
    }
};
