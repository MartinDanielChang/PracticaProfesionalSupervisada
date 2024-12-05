<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPSIA01 extends Model
{
    use HasFactory;

    // El nombre de la tabla (si no sigue la convención plural en inglés)
    protected $table = 'tbl_ppsia01';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'carrera',
        'practica',
        'dias',
        'horario',
        'fecha_inicio',
        'fecha_finalizacion',
        'modalidad',
        'correo_vinculacion',
        'actividad1',
        'actividad2',
        'actividad3',
        'actividad4',
        'actividad5',
        'total_horas',
        'ciudad',
        'fecha_emision',
        'jefe',
        'celular',
        'estado_nota',
        'fecha_ingreso',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function solicitudPPS()
    {
        return $this->belongsTo(SolicitudPPS::class, 'solicitud_id', 'id_solicitud'); // Aquí 'solicitud_id' es la clave foránea, y 'id_solicitud' es la clave primaria
    }
    

}
