<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class DatabaseBackupController extends Controller
{
    public function index()
    {
        return view('backup-database.index');
    }

    public function backup(Request $request)
    {
        try {
            Log::info("Mulai proses backup.");

            // Ambil konfigurasi database dari .env
            $dbHost = env('DB_HOST');
            $dbUser = env('DB_USERNAME');
            $dbPassword = env('DB_PASSWORD');
            $dbName = env('DB_DATABASE');
            $backupFile = storage_path('backups/' . 'cepatonline' . '_' . $dbName . '_' . date('Y-m-d_H-i-s') . '.sql');

            // Pastikan direktori backup ada
            if (!is_dir(storage_path('backups'))) {
                mkdir(storage_path('backups'), 0755, true);
            }

            // Buat file backup
            $fileHandle = fopen($backupFile, 'w');

            // Ambil semua tabel dari database
            $tables = DB::select('SHOW TABLES');

            foreach ($tables as $table) {
                $tableName = $table->{"Tables_in_$dbName"};

                // Tulis struktur tabel
                $createTable = DB::select("SHOW CREATE TABLE `$tableName`");
                fwrite($fileHandle, $createTable[0]->{'Create Table'} . ";\n\n");

                // Tulis data tabel
                $rows = DB::table($tableName)->get();
                foreach ($rows as $row) {
                    $values = array_map(function($value) {
                        return "'" . addslashes($value) . "'";
                    }, (array) $row);
                    $sql = "INSERT INTO `$tableName` VALUES (" . implode(',', $values) . ");\n";
                    fwrite($fileHandle, $sql);
                }

                fwrite($fileHandle, "\n\n");
            }

            fclose($fileHandle);

            Log::info("Proses backup selesai. File backup: " . $backupFile);
            return response()->download($backupFile)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Backup Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
