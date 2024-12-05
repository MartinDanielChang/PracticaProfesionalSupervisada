<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = 'roles';

    protected $primaryKey = 'COD_ROL';

    protected $fillable = [
        'NOM_ROL',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role', 'COD_ROL', 'user_id');
    }

    public function permisos()
    {
        return $this->hasMany(Permiso::class, 'COD_ROL', 'COD_ROL');
    }
}
