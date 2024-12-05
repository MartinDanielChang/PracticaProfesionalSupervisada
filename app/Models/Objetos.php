<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objetos extends Model
{
    use HasFactory;

    protected $table = 'objetos';

    protected $primaryKey = 'COD_OBJETO';

    protected $fillable = [
        'NOM_OBJETO',
        'TIP_OBJETO',
        'DES_OBJETO',
        'IND_OBJETO',
        'USR_ADD',
    ];

    public $timestamps = true;

}
