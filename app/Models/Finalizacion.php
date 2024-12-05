<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finalizacion extends Model
{
    use HasFactory;

    // Especifica la tabla si no sigue el nombre plural
    protected $table = 'tbl_nota_finalizacion';

    // Define los campos que se pueden llenar (fillables)
    protected $fillable = [
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
        // Relación con la tabla tbl_solicitudes, usando la clave foránea 'id_solicitud'
        return $this->belongsTo(SolicitudPPS::class, 'id_solicitud', 'id_solicitud');
    }

}
