<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colegiacion extends Model
{
    use HasFactory;

    protected $table = 'tbl_colegiacion';

    // Campos asignables masivamente
    protected $fillable = [
        'preinscripcion',
        'empresaPractica',
        'labora',
        'unidadDepartamento',
        'direccionExacta',
        'celularEmpresa',
        'telefonoFijo',
        'extension',
        'correoEmpresa',
        'cargo',
        'fechaIngreso',
        'estado_nota',
        'fecha_ingreso',
        'user_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Relación con el modelo User.
     * Una colegiación pertenece a un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
