<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cancelacion extends Model
{
    use HasFactory;

    // Especifica la tabla si no sigue el nombre plural
    protected $table = 'tbl_nota_cancelacion';

    // Define los campos que se pueden llenar (fillables)
    protected $fillable = [
        'estado_nota',
        'fecha_ingreso',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Modelo Colegiacion
public function solicitudPPS()
{
    return $this->belongsTo(SolicitudPPS::class, 'solicitud_id');
}

}
