<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupervisorEstudiantes extends Model
{
    use HasFactory;

    protected $table = 'supervisor_estudiantes';

    protected $fillable = [
        'supervisor_id',
        'estudiante_id',
        'supervision1_file',
        'supervision2_file',
    ];

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class, 'supervisor_id', 'id_supervisor');
        
    }
    
    
    public function estudiante()
    {
        return $this->belongsTo(User::class, 'estudiante_id', 'id');
    }

    public function getSupervision1FilePathAttribute()
{
    return $this->supervision1_file ? asset('storage/' . $this->supervision1_file) : null;
}

public function getSupervision2FilePathAttribute()
{
    return $this->supervision2_file ? asset('storage/' . $this->supervision2_file) : null;
}

    
}

