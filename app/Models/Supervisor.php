<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Supervisor extends Model
{
    use HasFactory;

    protected $table = 'tbl_supervisores';

    protected $primaryKey = 'id_supervisor';

    protected $fillable = ['nombre', 'contacto', 'no_estudiantes', 'estado'];

    protected $attributes = [
        'estado' => 'HABILITADO', // Valor predeterminado
    ];

    public function estudiantes()
{
    return $this->hasMany(SupervisorEstudiantes::class, 'supervisor_id', 'id_supervisor');
}
    protected static function boot()
    {
        parent::boot();

        // Evento que se dispara cuando un supervisor es creado o actualizado
        static::created(function ($supervisor) {
            $supervisor->asignarEstudiantes();
        });

        static::updated(function ($supervisor) {
            if ($supervisor->estado === 'HABILITADO') {
                $supervisor->asignarEstudiantes();
            }
        });
    }



/**
 * Asignar estudiantes al supervisor habilitado.
 */
public function asignarEstudiantes()
{
    DB::transaction(function () {
        $limiteEstudiantes = 3; // Límite fijo de estudiantes por supervisor

        // Verificar cuántos estudiantes ya tiene asignados el supervisor
        $estudiantesActuales = SupervisorEstudiantes::where('supervisor_id', $this->id_supervisor)->count();

        // Si ya tiene el límite máximo, no hacer nada
        if ($estudiantesActuales >= $limiteEstudiantes) {
            return;
        }

        // Obtener IDs de estudiantes ya asignados a supervisores
        $estudiantesAsignados = SupervisorEstudiantes::pluck('estudiante_id')->toArray();

        // Obtener estudiantes con solicitudes aprobadas y sin supervisor asignado
        $estudiantesNoAsignados = User::where('COD_ROL', 2) // Rol 2: Estudiante
            ->whereHas('solicitudes', function ($query) {
                $query->where('estado_solicitud', 'APROBADA');
            })
            ->whereNotIn('id', $estudiantesAsignados)
            ->inRandomOrder()
            ->take($limiteEstudiantes - $estudiantesActuales) // Asignar solo los necesarios
            ->get();

        foreach ($estudiantesNoAsignados as $estudiante) {
            SupervisorEstudiantes::create([
                'supervisor_id' => $this->id_supervisor,
                'estudiante_id' => $estudiante->id,
            ]);
        }
    });
}



}
