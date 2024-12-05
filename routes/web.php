<?php
use Laravel\Fortify\Features;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormatosController;
use App\Http\Controllers\SolicitudesPPSController;
use App\Http\Controllers\SolicitudesTrabajoController;
use App\Http\Controllers\SolicitudesActualizacionController;
use App\Http\Controllers\MantenimientoUsuarioController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermisosController;
use App\Http\Controllers\ObjetosController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupervisorController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['middleware' => ['auth']], function() {
    Route::resource('formatos',FormatosController::class)->names('formatos');  
    Route::post('/subir-archivo', [FormatosController::class, 'subirArchivo'])->name('subirArchivo');
    Route::delete('/archivos/eliminar/{archivo}', [FormatosController::class, 'eliminar'])->name('eliminar.archivo');
});

Route::group(['middleware' => ['auth']], function() {
    Route::get('/supervisores', [SupervisorController::class, 'index'])->name('supervisores.index');
    Route::post('/supervisores', [SupervisorController::class, 'store'])->name('supervisores.store');
    Route::put('/supervisores/{id}/habilitar', [SupervisorController::class, 'habilitar'])->name('supervisores.habilitar');
Route::put('/supervisores/{id}/inhabilitar', [SupervisorController::class, 'inhabilitar'])->name('supervisores.inhabilitar');
Route::post('/supervisores/asignar', [SupervisorController::class, 'asignarEstudiantesAleatoriamente'])->name('supervisores.asignar');
Route::post('/supervisores/{id}/subirArchivo', [SupervisorController::class, 'subirArchivo'])->name('supervisores.subirArchivo');
Route::get('/supervisores/mis-estudiantes', [SupervisorController::class, 'misEstudiantes'])
    ->name('supervisores.mis_estudiantes');

    Route::post('/supervisores/procesar-finalizados', [SupervisorController::class, 'procesarFinalizados'])->name('supervisores.procesarFinalizados');
    Route::put('/supervisores/{id}/actualizarMaxEstudiantes', [SupervisorController::class, 'actualizarMaxEstudiantes'])
    ->name('supervisores.actualizarMaxEstudiantes');


    // Recursos
    Route::resource('solicitudes', SolicitudesPPSController::class)->names('solicitudes');
    Route::get('/solicitudes/{id}/download', [SolicitudesPPSController::class, 'download'])->name('solicitudes.download');
    Route::resource('solicitudportrabajo', SolicitudesTrabajoController::class)->names('solicitudportrabajo');

    // Rutas adicionales
    Route::get('/scan-tables', [SolicitudesTrabajoController::class, 'scanTables'])->name('scanTables');
    Route::post('/colegiacion/store', [\App\Http\Controllers\ColegiacionController::class, 'store'])->name('colegiacion.store');
    Route::post('/subir-archivo', [\App\Http\Controllers\ColegiacionController::class, 'subirArchivo'])->name('subirArchivo');
    Route::post('/subir-presentacion', [\App\Http\Controllers\PresentacionController::class, 'subirPresentacion'])->name('subirPresentacion');
    Route::post('/subir-ia01', [\App\Http\Controllers\PPSIA01Controller::class, 'subirIA01'])->name('subirIA01');
    Route::post('/subir-ia02', [\App\Http\Controllers\PPSIA02Controller::class, 'subirIA02'])->name('subirIA02');
    Route::post('/subir-aceptacion', [\App\Http\Controllers\AceptacionController::class, 'subirAceptacion'])->name('subirAceptacion');
    Route::post('/subir-finalizacion', [\App\Http\Controllers\FinalizacionController::class, 'subirFinalizacion'])->name('subirFinalizacion');
});



Route::group(['middleware' => ['auth']], function() {
    Route::resource('actualizacionpps',SolicitudesActualizacionController::class)->names('actualizacionpps');  
    Route::post('/actualizacionpps/actualizar-datos', [SolicitudesActualizacionController::class, 'actualizarDatos'])->name('actualizar-datos');
});

Route::group(['middleware' => ['auth']], function() {
    Route::resource('cancelacionpps',\App\Http\Controllers\CancelacionController::class)->names('cancelacionpps');  
});

Route::resource('roles', \App\Http\Controllers\RoleController::class);
Route::post('/roles', '\App\Http\Controllers\RoleController@store')->name('roles.store');
Route::post('/roles/assign', '\App\Http\Controllers\RoleController@assignRole')->name('roles.assign');

Route::group(['middleware' => ['auth']], function() {
    Route::post('/mantenimientousuario/store', [MantenimientoUsuarioController::class, 'store'])->name('mantenimientousuario.store');
    Route::resource('mantenimientousuario', MantenimientoUsuarioController::class)->names('mantenimientousuario');
    
    Route::get('roles_permisos', function () {
        $roles = App\Models\Roles::all();
        $permisos = App\Models\Permiso::with('role', 'objeto')->get();
        $objetos = App\Models\Objetos::all();
        return view('roles_permisos', compact('roles', 'permisos', 'objetos'));
    })->name('roles_permisos.index');
    

    Route::get('roles_permisos', [RoleController::class, 'index'])->name('roles_permisos.index');

    // Rutas para roles
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
    Route::put('roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');
    
    // Rutas para permisos
    Route::post('permisos', [PermisosController::class, 'store'])->name('permisos.store');
    Route::put('permisos/{permiso}', [PermisosController::class, 'update'])->name('permisos.update');
    Route::delete('permisos/{permiso}', [PermisosController::class, 'destroy'])->name('permisos.destroy');

    Route::resource('objetos', ObjetosController::class)->names('objetos');



});


Route::resource('respaldo', \App\Http\Controllers\RespaldoController::class);
Route::post('/respaldo/backup', [\App\Http\Controllers\RespaldoController::class, 'backup'])->name('respaldo.backup');
Route::post('/respaldo/restore', [\App\Http\Controllers\RespaldoController::class, 'restore'])->name('respaldo.restore');

Route::group(['middleware' => ['auth']], function() {
    Route::resource('expediente', \App\Http\Controllers\ExpedienteController::class)->names('expediente');
    Route::get('/expediente/{expediente}', [ExpedienteController::class, 'show'])->name('expediente.show');
});

require __DIR__.'/auth.php';
