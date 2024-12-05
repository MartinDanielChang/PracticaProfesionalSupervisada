<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DatabaseBackup extends Command
{
    protected $backupPath;
    protected $signature = 'database:backup';
    protected $description = 'Backup the database';

    public function __construct()
    {
        $this->backupPath = storage_path('storage/backups');
        parent::__construct();
    }

    // Función para limpiar variables de SQL
    public static function limpiarCadena($valor)
    {
        $search = ["<script>", "</script>", "SELECT * FROM", "DELETE FROM", "UPDATE", "INSERT INTO", "DROP TABLE", "TRUNCATE TABLE", "--", "^", "[", "]", "\\", "="];
        $replace = ["", "", "", "", "", "", "", "", "", "", "", "", "", "", "", ""];
        return str_ireplace($search, $replace, $valor);
    }

    // Método para ejecutar el backup
    public function handle()
    {
        $result = $this->createBackup();

        if ($result['status']) {
            $this->info($result['message']);
            $this->createRedirectFile('/respaldo');
        } else {
            $this->error($result['message']);
        }
    }

    // Crear respaldo de la base de datos
    public function createBackup()
    {
        $date = now();
        $filename = "backup_" . $date->format('d_m_Y') . ".sql";
        $sql = $this->getDatabaseBackupSQL();

        if (!File::exists($this->backupPath)) {
            File::makeDirectory($this->backupPath, 0777, true);
        }

        if (File::put($this->backupPath . '/' . $filename, $sql)) {
            return ['status' => true, 'message' => 'Backup successful', 'file' => $filename];
        } else {
            return ['status' => false, 'message' => 'Error creating backup'];
        }
    }

    // Obtener el SQL de la base de datos
    private function getDatabaseBackupSQL()
    {
        $tables = DB::select('SHOW TABLES');
        $sql = 'SET FOREIGN_KEY_CHECKS=0;' . "\n\n";
        $sql .= 'CREATE DATABASE IF NOT EXISTS ' . env('DB_DATABASE') . ";\n\n";
        $sql .= 'USE ' . env('DB_DATABASE') . ";\n\n";

        foreach ($tables as $table) {
            $tableName = $table->{"Tables_in_" . env('DB_DATABASE')};
            $createTableSQL = DB::select("SHOW CREATE TABLE {$tableName}");
            $sql .= $createTableSQL[0]->{'Create Table'} . ";\n\n";

            $rows = DB::table($tableName)->get();
            foreach ($rows as $row) {
                $sql .= 'INSERT INTO ' . $tableName . ' VALUES(';
                foreach ($row as $key => $value) {
                    $value = addslashes($value);
                    $value = str_replace("\n", "\\n", $value);
                    $sql .= '"' . $value . '"';
                    if ($key !== array_key_last((array)$row)) {
                        $sql .= ',';
                    }
                }
                $sql .= ");\n";
            }
            $sql .= "\n\n";
        }

        $sql .= 'SET FOREIGN_KEY_CHECKS=1;';

        return $sql;
    }

    // Crear archivo de redirección
    private function createRedirectFile($url)
    {
        $filePath = public_path('backup_redirect.txt');
        File::put($filePath, $url);
    }
}
