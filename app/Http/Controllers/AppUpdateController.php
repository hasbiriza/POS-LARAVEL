<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class AppUpdateController extends Controller
{
    public function index()
{
    // Mendapatkan daftar file ZIP yang ada di dalam folder 'updates'
    $updatePath = storage_path('app/updates');
    $zipFiles = glob($updatePath . '/*.zip'); // Menampilkan file dengan ekstensi .zip

    return view('update.index', compact('zipFiles'));
}


    public function upload(Request $request)
{
    $request->validate([
        'update_file' => 'required|file|max:102400', // Maksimal 100MB (102400 KB)
    ]);

    if ($request->file('update_file')->isValid()) {
        $file = $request->file('update_file');
        $file->move(storage_path('app/updates'), $file->getClientOriginalName());

        return redirect()->route('update.index')->with('success', 'File update berhasil diunggah.');
    }

    return redirect()->route('update.index')->with('error', 'Gagal mengunggah file.');
}

    


public function run()
{
    $updatePath = storage_path('app/updates');
    $zipFile = glob($updatePath . '/*.zip')[0] ?? null;

    if ($zipFile) {
        $extractPath = base_path(); // Ekstrak ke root aplikasi
        $zip = new \ZipArchive;

        if ($zip->open($zipFile) === TRUE) {
            $zip->extractTo($extractPath);
            $zip->close();

            File::delete($zipFile);

            return redirect()->route('update.index')->with('success', 'Aplikasi berhasil diperbarui.');
        } else {
            return redirect()->route('update.index')->with('error', 'Gagal membuka file ZIP.');
        }
    }

    return redirect()->route('update.index')->with('error', 'Tidak ada file update yang ditemukan.');
}

}
