<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    // Especificar la tabla asociada al modelo
    protected $table = 'permisos';

    // Especificar la clave primaria personalizada
    protected $primaryKey = 'COD_PERMISOS';

    // Laravel no espera que el campo auto-incremental sea modificado, por lo que no debe incluirse en $fillable
    protected $fillable = [
        'COD_ROL',
        'COD_OBJETO',
        'IND_MODULO',
        'IND_SELECT',
        'IND_INSERT',
        'IND_UPDATE',
        'USR_ADD',
    ];

    // Desactiva las marcas de tiempo automáticas si no las usas
    public $timestamps = true;

    /**
     * Relación: Un permiso pertenece a un rol.
     */
    public function role()
    {
        return $this->belongsTo(Roles::class, 'COD_ROL');
    }

    /**
     * Relación: Un permiso pertenece a un objeto.
     */
    public function objeto()
    {
        return $this->belongsTo(Objetos::class, 'COD_OBJETO');
    }
}
