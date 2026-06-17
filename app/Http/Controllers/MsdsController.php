<?php

namespace App\Http\Controllers;

use App\Models\RaBahanKimia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MsdsController extends Controller
{
    /**
     * Download MSDS: salin ke public sementara, redirect, file dilayani langsung.
     * Menghindari korupsi PDF saat stream lewat Laravel.
     */
    public function show(int $id)
    {
        $bahan = RaBahanKimia::findOrFail($id);

        if (! $bahan->msds_file) {
            abort(404, 'File MSDS tidak ditemukan.');
        }

        if (! Auth::check()) {
            abort(403, 'Anda harus login untuk mengakses file MSDS.');
        }

        $fullPath = Storage::disk('public')->path($bahan->msds_file);

        if (! is_file($fullPath) || ! is_readable($fullPath)) {
            abort(404, 'File MSDS tidak ditemukan di server.');
        }

        $tempDir = public_path('temp-msds');
        $tempFile = $tempDir.'/'.uniqid('msds_', true).'.pdf';

        if (! File::isDirectory($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        $this->cleanOldTempFiles($tempDir);

        if (! copy($fullPath, $tempFile)) {
            abort(500, 'Gagal memproses file.');
        }

        // Redirect ke file di public - dilayani langsung oleh web server
        $url = asset('temp-msds/'.basename($tempFile));

        return redirect($url);
    }

    private function cleanOldTempFiles(string $dir): void
    {
        $files = File::glob($dir.'/msds_*.pdf');
        $maxAge = 3600;
        foreach ($files as $file) {
            if (filemtime($file) < time() - $maxAge) {
                @unlink($file);
            }
        }
    }
}
