<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPSIA02 extends Model
{
    use HasFactory;

    protected $table = 'tbl_ppsia02';

    // Campos asignables masivamente
    protected $fillable = [
        'user_id',
        'hora_inicio_lunes_viernes1',
        'hora_fin_lunes_viernes1',
        'hora_inicio_lunes_viernes2',
        'hora_fin_lunes_viernes2',
        'hora_inicio_sabado',
        'hora_fin_sabado',
        'puesto',
        'departamento',
        'fecha_ingreso_institucion',
        'fecha_inicio_puesto',
        'jornada_laboral',
        'nombre_institucion',
        'direccion_institucion',
        'tipo_institucion',
        'fecha_constitucion',
        'nombre_jefe',
        'cargo_jefe',
        'correo_jefe',
        'telefono_jefe',
        'celular_jefe',
        'nivel_academico',
        'archivo', // Nombre del archivo PDF almacenado
    ];

    // Desactivar timestamps si la tabla no los usa
    public $timestamps = true; // Cambia a false si no usas created_at y updated_at

    /**
     * Relación con el modelo User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id'); // user_id es la clave foránea
    }

    /**
     * Relación con el modelo SolicitudPPS
     */
    public function solicitudPPS()
    {
        return $this->belongsTo(SolicitudPPS::class, 'solicitud_id', 'id'); // solicitud_id es la clave foránea
    }
    // En el modelo User
public function solicitudes()
{
    return $this->hasMany(SolicitudPPS::class, 'user_id', 'id');
}

}
