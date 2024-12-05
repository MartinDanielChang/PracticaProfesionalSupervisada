<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\DatabaseBackup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class RespaldoController extends Controller
{
    // Propiedad para el servicio de respaldo
    protected $backupService;

    // Constructor que inyecta el servicio de respaldo
    public function __construct(DatabaseBackup $backupService)
    {
        $this->backupService = $backupService;
    }

    /**
     * Muestra el listado de los archivos de respaldo disponibles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Lee los archivos en el directorio de backups (corregido el path)
        $backups = array_diff(scandir(storage_path('storage/backups')), ['.', '..']);

        // Retorna la vista con la lista de backups
        return view('respaldo.index', compact('backups'));
    }

    public function restore(Request $request)
    {
        $restorePoint = $request->input('restorePoint');
        $sqlFile = storage_path("storage/backups/$restorePoint");

        if (!File::exists($sqlFile)) {
            return response()->json(['error' => "El archivo SQL no existe: $sqlFile"], 404);
        }

        $pdo = DB::connection()->getPdo();

        try {
            // Eliminar y recrear la base de datos
            $dbName = env('DB_DATABASE');
            $pdo->exec("DROP DATABASE IF EXISTS `$dbName`");
            $pdo->exec("CREATE DATABASE `$dbName`");
            $pdo->exec("USE `$dbName`");

            // Leer y ejecutar el contenido del archivo SQL
            $sqlContent = File::get($sqlFile);
            $existingTables = $this->getExistingTables($pdo);

            $sqlCommands = explode(';', $sqlContent);

            foreach ($sqlCommands as $command) {
                $command = trim($command);

                if (empty($command)) {
                    continue;
                }

                // Omitir creación de tablas existentes y usar "INSERT IGNORE"
                if (preg_match('/^CREATE TABLE `?(\w+)`?/i', $command, $matches)) {
                    $tableName = $matches[1];
                    if (in_array($tableName, $existingTables)) {
                        continue;
                    }
                }

                if (stripos($command, 'INSERT INTO') === 0) {
                    $command = str_ireplace('INSERT INTO', 'INSERT IGNORE INTO', $command);
                }

                $pdo->exec($command);
            }

            return response()->json(['success' => 'Restauración de la base de datos completada con éxito.']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error durante la restauración: ' . $e->getMessage()], 500);
        }
    }

    private function getExistingTables($pdo)
    {
        $tables = [];
        $result = $pdo->query("SHOW TABLES");

        while ($row = $result->fetch(\PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }

        return $tables;
    }

    /**
     * Realiza un respaldo de la base de datos.
     *
     * @return \Illuminate\Http\Response
     */
    public function backup()
    {
        // Llama al servicio para crear un respaldo
        return $this->backupService->createBackup();
    }

    /**
     * Restaura la base de datos desde un archivo de respaldo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Muestra el formulario para crear un nuevo recurso.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Almacena un nuevo recurso en el almacenamiento.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Muestra el recurso especificado.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Muestra el formulario para editar el recurso especificado.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Actualiza el recurso especificado en el almacenamiento.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Elimina el recurso especificado del almacenamiento.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
