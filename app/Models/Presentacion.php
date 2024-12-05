<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Presentacion extends Model
{
    use HasFactory;

    // Especifica la tabla si no sigue el nombre plural
    protected $table = 'tbl_nota_presentacion';

    // Define los campos que se pueden llenar (fillables)
    protected $fillable = [
        'estado_nota',
        'fecha_ingreso',
        'user_id',
        'solicitud_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function solicitudPPS()
    {
        return $this->belongsTo(SolicitudPPS::class, 'solicitud_id', 'id_solicitud'); 
    }
    
}
