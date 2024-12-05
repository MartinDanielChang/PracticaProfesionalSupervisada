<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'COD_ROL', // Incluye este campo para roles
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Boot method to handle role assignment and supervisor creation.
     */
    protected static function boot()
    {
        parent::boot();

        // Asignar rol automáticamente según el correo electrónico al crear un usuario
        static::creating(function ($user) {
            if (!$user->COD_ROL) {
                $user->COD_ROL = self::determineRole($user->email);
            }
        });

        // Crear registro en tbl_supervisores si el usuario es un Supervisor (COD_ROL = 3)
        static::created(function ($user) {
            if ($user->COD_ROL == 3 && str_ends_with($user->email, '@unah.edu.hn')) {
                Supervisor::create([
                    'nombre' => $user->name,
                    'contacto' => $user->email,
                    'no_estudiantes' => 0,
                ]);
            }
        });
    }

    /**
     * Determinar el rol basado en el correo electrónico.
     *
     * @param string $email
     * @return int
     */
    public static function determineRole(string $email): int
    {
        $domain = substr(strrchr($email, "@"), 1);

        if ($email === 'adminpps.dia@unah.edu.hn') {
            return 1; // Administrador
        }

        if (str_ends_with($email, '@unah.edu.hn')) {
            return 3; // Supervisor
        }

        if (str_ends_with($email, '@unah.hn')) {
            return 2; // Estudiante
        }

        throw new \Exception("El dominio del correo '{$domain}' no está permitido.");
    }

    /**
     * Relación con roles (un usuario tiene un rol).
     */
    public function role()
    {
        return $this->belongsTo(Roles::class, 'COD_ROL', 'COD_ROL');
    }

    /**
     * Relación con permisos a través de roles.
     */
    public function permisos()
    {
        return $this->hasManyThrough(Permiso::class, Roles::class, 'COD_ROL', 'COD_ROL', 'COD_ROL', 'COD_ROL');
    }

    /**
     * Relación con solicitudes.
     */
   public function solicitudes()
{
    return $this->hasMany(SolicitudPPS::class, 'user_id', 'id');
}

public function supervisorEstudiantes()
{
    return $this->hasOne(SupervisorEstudiantes::class, 'estudiante_id', 'id');
}


public function supervisor()
{
    return $this->hasOne(Supervisor::class, 'contacto', 'email');
}


    /**
     * Método para almacenar usuarios usando un procedimiento almacenado.
     */
    public static function storeUser(Request $request)
    {
        try {
            // Validar los datos de entrada
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'no_cuenta' => 'required|numeric',
                'no_identidad' => 'required|numeric',
                'password' => 'required|string|min:8',
            ]);

            $email = $validated['email'];
            $codRol = self::determineRole($email);

            // Llamar al procedimiento almacenado para insertar el usuario
            DB::statement('CALL InsertUser(?, ?, ?, ?, ?, ?)', [
                $validated['name'],
                $email,
                $validated['no_cuenta'],
                $validated['no_identidad'],
                $codRol,
                Hash::make($validated['password']),
            ]);

            // Si el usuario es supervisor, agregarlo a tbl_supervisores
            if ($codRol == 3) {
                Supervisor::create([
                    'nombre' => $validated['name'],
                    'contacto' => $email,
                    'no_estudiantes' => 0,
                ]);
            }

            return redirect()->back()->with('success', '¡Usuario creado exitosamente!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Método para actualizar usuarios utilizando un procedimiento almacenado.
     */
    public static function updateUser($id, Request $request)
    {
        DB::statement('CALL UpdateUser(?, ?, ?, ?, ?, ?)', [
            $id,
            $request->input('name'),
            $request->input('email'),
            $request->input('no_identidad'),
            $request->input('no_cuenta'),
            $request->input('COD_ROL'),
        ]);
    }

    /**
     * Verificar si el usuario tiene un rol específico.
     */
    public function hasRole($roleName)
    {
        return $this->role && $this->role->nombre === $roleName;
    }

    /**
     * Asignar un rol a un usuario.
     */
    public function assignRole($roleId)
    {
        $this->COD_ROL = $roleId;
        $this->save();
    }

    
}
