<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;
use ZipArchive;

class BackupController extends Controller
{
    /**
     * Tampilkan halaman Backup Database & Storage.
     */
    public function index()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Akses ditolak.');
        }

        $dbConnection = config('database.default');
        $dbName = config("database.connections.{$dbConnection}.database");

        // Hitung informasi direktori storage
        $storagePath = storage_path('app/public');
        $storageFileCount = 0;
        $storageTotalSize = 0;

        if (File::exists($storagePath)) {
            $files = File::allFiles($storagePath);
            $storageFileCount = count($files);
            foreach ($files as $file) {
                $storageTotalSize += $file->getSize();
            }
        }

        $formattedStorageSize = $this->formatBytes($storageTotalSize);

        return view('pages.backup.index', compact(
            'dbConnection',
            'dbName',
            'storageFileCount',
            'formattedStorageSize'
        ));
    }

    /**
     * Ekspor dan unduh seluruh database MySQL dalam format .sql.
     */
    public function downloadDatabase()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Akses ditolak.');
        }

        $dbName = config('database.connections.mysql.database');
        $fileName = 'backup-' . $dbName . '-' . date('Y-m-d_H-i-s') . '.sql';

        return response()->streamDownload(function () use ($dbName) {
            $handle = fopen('php://output', 'w');

            // Header file SQL
            fwrite($handle, "-- ========================================================\n");
            fwrite($handle, "-- SIHUMAS Universitas Ibnu Sina - Database Backup\n");
            fwrite($handle, "-- Tanggal / Waktu : " . date('Y-m-d H:i:s') . "\n");
            fwrite($handle, "-- Database        : `{$dbName}`\n");
            fwrite($handle, "-- ========================================================\n\n");
            fwrite($handle, "SET NAMES utf8mb4;\n");
            fwrite($handle, "SET FOREIGN_KEY_CHECKS = 0;\n");
            fwrite($handle, "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n");
            fwrite($handle, "SET AUTOCOMMIT = 0;\n");
            fwrite($handle, "START TRANSACTION;\n\n");

            // Ambil semua tabel
            $tablesResult = DB::select('SHOW TABLES');
            $tableKey = 'Tables_in_' . $dbName;

            foreach ($tablesResult as $tableObj) {
                $tableName = isset($tableObj->$tableKey) ? $tableObj->$tableKey : array_values((array) $tableObj)[0];

                // Struktur Tabel
                fwrite($handle, "-- --------------------------------------------------------\n");
                fwrite($handle, "-- Struktur dari tabel `{$tableName}`\n");
                fwrite($handle, "-- --------------------------------------------------------\n");
                fwrite($handle, "DROP TABLE IF EXISTS `{$tableName}`;\n");

                $createTableResult = DB::select("SHOW CREATE TABLE `{$tableName}`");
                if (!empty($createTableResult)) {
                    $createTableSql = $createTableResult[0]->{'Create Table'};
                    fwrite($handle, $createTableSql . ";\n\n");
                }

                // Data Tabel (chunked agar hemat memori)
                fwrite($handle, "-- Data untuk tabel `{$tableName}`\n");
                $pdo = DB::getPdo();

                DB::table($tableName)->orderBy(DB::raw('1'))->chunk(200, function ($rows) use ($handle, $tableName, $pdo) {
                    foreach ($rows as $row) {
                        $rowArray = (array) $row;
                        $values = [];

                        foreach ($rowArray as $val) {
                            if (is_null($val)) {
                                $values[] = 'NULL';
                            } elseif (is_numeric($val) && !is_string($val)) {
                                $values[] = $val;
                            } else {
                                $values[] = $pdo->quote((string) $val);
                            }
                        }

                        $fields = '`' . implode('`, `', array_keys($rowArray)) . '`';
                        $valString = implode(', ', $values);
                        fwrite($handle, "INSERT INTO `{$tableName}` ({$fields}) VALUES ({$valString});\n");
                    }
                });

                fwrite($handle, "\n");
            }

            // Footer file SQL
            fwrite($handle, "SET FOREIGN_KEY_CHECKS = 1;\n");
            fwrite($handle, "COMMIT;\n");
            fwrite($handle, "-- Backup selesai dibuat.\n");

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }

    /**
     * Impor atau pulihkan database dari berkas .sql yang diunggah.
     */
    public function restoreDatabase(Request $request)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'sql_file' => 'required|file|max:51200', // maks 50 MB
        ], [
            'sql_file.required' => 'Pilih berkas backup SQL terlebih dahulu.',
            'sql_file.max' => 'Ukuran berkas SQL maksimal 50 MB.',
        ]);

        $file = $request->file('sql_file');
        $ext = strtolower($file->getClientOriginalExtension());

        if (!in_array($ext, ['sql', 'txt'])) {
            Alert::error('Gagal', 'Format berkas harus berekstensi .sql')->toToast()->autoclose(3000)->timerProgressBar();
            return redirect()->back();
        }

        try {
            $sqlContent = file_get_contents($file->getRealPath());

            if (empty(trim($sqlContent))) {
                Alert::error('Gagal', 'Berkas SQL kosong.')->toToast()->autoclose(3000)->timerProgressBar();
                return redirect()->back();
            }

            // Eksekusi pemulihan database
            DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
            DB::unprepared($sqlContent);
            DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

            Alert::success('Berhasil', 'Database berhasil dipulihkan dari berkas backup.')->toToast()->autoclose(3000)->timerProgressBar();
        } catch (\Throwable $th) {
            Alert::error('Error', 'Gagal memulihkan database: ' . $th->getMessage())->toToast()->autoclose(4000)->timerProgressBar();
        }

        return redirect()->route('backup.index');
    }

    /**
     * Arsipkan dan unduh seluruh berkas storage/app/public dalam berkas .zip.
     */
    public function downloadStorage()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Akses ditolak.');
        }

        $storagePath = storage_path('app/public');

        if (!File::exists($storagePath)) {
            Alert::error('Gagal', 'Folder storage/app/public tidak ditemukan.')->toToast()->autoclose(3000)->timerProgressBar();
            return redirect()->back();
        }

        $zipFileName = 'backup-storage-' . config('app.name', 'sihumas') . '-' . date('Y-m-d_H-i-s') . '.zip';
        $tempDir = storage_path('app/temp-backups');

        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        $zipFilePath = $tempDir . DIRECTORY_SEPARATOR . $zipFileName;

        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            Alert::error('Gagal', 'Tidak dapat membuat berkas ZIP.')->toToast()->autoclose(3000)->timerProgressBar();
            return redirect()->back();
        }

        $files = File::allFiles($storagePath);

        foreach ($files as $file) {
            $relativePath = str_replace($storagePath . DIRECTORY_SEPARATOR, '', $file->getRealPath());
            $relativePath = str_replace('\\', '/', $relativePath);
            $zip->addFile($file->getRealPath(), $relativePath);
        }

        $zip->close();

        return response()->download($zipFilePath, $zipFileName)->deleteFileAfterSend(true);
    }

    /**
     * Impor atau pulihkan isi storage dari berkas .zip yang diunggah.
     */
    public function restoreStorage(Request $request)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'zip_file' => 'required|file|mimes:zip|max:204800', // maks 200 MB
        ], [
            'zip_file.required' => 'Pilih berkas backup ZIP terlebih dahulu.',
            'zip_file.mimes' => 'Berkas harus berupa format .zip.',
            'zip_file.max' => 'Ukuran berkas ZIP maksimal 200 MB.',
        ]);

        $file = $request->file('zip_file');
        $targetPath = storage_path('app/public');

        if (!File::exists($targetPath)) {
            File::makeDirectory($targetPath, 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($file->getRealPath()) === true) {
            // Ekstrak file secara aman
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entryName = $zip->getNameIndex($i);

                // Cegah Zip Slip vulnerability (path traversal "../")
                if (str_contains($entryName, '..') || str_starts_with($entryName, '/') || str_starts_with($entryName, '\\')) {
                    continue;
                }

                $zip->extractTo($targetPath, [$entryName]);
            }
            $zip->close();

            Alert::success('Berhasil', 'Berkas storage public berhasil dipulihkan.')->toToast()->autoclose(3000)->timerProgressBar();
        } else {
            Alert::error('Gagal', 'Tidak dapat membaca atau mengekstrak berkas ZIP.')->toToast()->autoclose(3000)->timerProgressBar();
        }

        return redirect()->route('backup.index');
    }

    /**
     * Format byte ke satuan yang mudah dibaca (KB, MB, GB).
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $base = log($bytes, 1024);
        $floor = (int) floor($base);

        return round(pow(1024, $base - $floor), $precision) . ' ' . ($units[$floor] ?? 'B');
    }
}

