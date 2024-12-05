<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class RestoreDatabase extends Command
{
    protected $signature = 'db:restore {restorePoint}';
    protected $description = 'Restaura la base de datos desde un archivo SQL';

    public function handle()
    {
        $restorePoint = $this->argument('restorePoint');
        $sqlFile = storage_path("backups/$restorePoint");

        if (!File::exists($sqlFile)) {
            $this->error("El archivo SQL no existe: $sqlFile");
            return;
        }

        // Crear conexión PDO usando configuración de Laravel
        $pdo = DB::connection()->getPdo();

        try {
            // Eliminar la base de datos actual
            $dbName = env('DB_DATABASE');
            $pdo->exec("DROP DATABASE IF EXISTS `$dbName`");
            $pdo->exec("CREATE DATABASE `$dbName`");
            $pdo->exec("USE `$dbName`");

            // Leer y ejecutar el contenido del archivo SQL
            $sqlContent = File::get($sqlFile);
            $existingTables = $this->getExistingTables($pdo);

            // Dividir las consultas por ';'
            $sqlCommands = explode(';', $sqlContent);

            foreach ($sqlCommands as $command) {
                $command = trim($command);

                if (empty($command)) {
                    continue;
                }

                // Evitar crear tablas existentes y hacer "INSERT IGNORE"
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

            $this->info("Restauración de la base de datos completada con éxito.");

            // **Redirigir a la URL deseada**
            $this->createRedirectFile('/respaldo');

        } catch (\Exception $e) {
            $this->error("Error durante la restauración de la base de datos: " . $e->getMessage());
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

    private function createRedirectFile($url)
    {
        $filePath = public_path('restore_redirect.txt');
        File::put($filePath, $url);
    }
}
