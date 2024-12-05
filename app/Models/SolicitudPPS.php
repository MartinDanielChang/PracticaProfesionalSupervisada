<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudPPS extends Model
{
    use HasFactory;


    public function index()
    {
        $solicitudes = SolicitudPPS::with(['user', 'supervisorEstudiante.supervisor'])
            ->get();
    
        return view('solicitudes.index', compact('solicitudes'));
    }
    
    // Especificar la tabla asociada al modelo
    protected $table = 'tbl_solicitudes';

    // Especificar la clave primaria personalizada
    protected $primaryKey = 'id_solicitud';

    // Habilitar marcas de tiempo si la tabla tiene columnas `created_at` y `updated_at`
    public $timestamps = true;

    // Campos asignables masivamente
    protected $fillable = [
        'estado_solicitud',
        'fecha_solicitud',
        'descripcion',
        'tipo_practica',
        'user_id', // Relación con el usuario
        'archivo_path', // Ruta del archivo subido
    ];

    /**
     * Relación con el modelo User.
     * Una solicitud pertenece a un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Método para obtener el nombre del usuario asociado.
     * Si no hay usuario, devuelve "Sin usuario".
     */
    public function getUserNameAttribute()
    {
        return $this->user ? $this->user->name : 'Sin usuario';
    }

    /**
     * Acceso al archivo descargable.
     */
    public function getArchivoUrlAttribute()
    {
        return $this->archivo_path ? asset('storage/' . $this->archivo_path) : null;
    }


    public function estudiante()
{
    return $this->belongsTo(User::class, 'user_id', 'id');
}

public function supervisorEstudiante()
{
    return $this->hasOne(SupervisorEstudiantes::class, 'estudiante_id', 'user_id');
}


    /**
     * Relación con el modelo NotaAceptacion.
     * Una solicitud puede tener muchas notas de aceptación.
     */
    public function notasAceptacion()
    {
        return $this->hasMany(NotaAceptacion::class, 'id_solicitud', 'id_solicitud');
    }
}
