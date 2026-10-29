<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class LibreOfficeService
{
    /**
     * Cari PDF hasil konversi sebelumnya yang masih fresh, yaitu:
     * 1. Dibuat SEBELUM request ini dimulai ($minMtime = waktu mulai request),
     *    supaya PDF yang baru saja digenerate untuk download Word/PDF di request
     *    yang sama tidak ikut dipakai (mencegu PDF basi saat data sudah berubah).
     * 2. Dibuat setelah data terakhir diubah ($lastUpdatedAt), kalau diberikan.
     * Kalau cocok, download berikutnya langsung sajikan file tanpa konversi ulang
     * yang memakan waktu belasan detik dan memblokir server dev single-threaded.
     */
    public static function findFreshCachedPdf(string $prefix, ?string $lastUpdatedAt = null, ?int $minMtime = null): ?string
    {
        $dir = storage_path('app/public');
        $files = glob($dir . '/' . $prefix . '_*.pdf');
        if ($files === false || empty($files)) {
            return null;
        }

        // Pakai PDF terbaru yang ada
        usort($files, fn ($a, $b) => filemtime($b) <=> filemtime($a));
        $latest = $files[0];

        if ($minMtime !== null && filemtime($latest) >= $minMtime) {
            return null; // file dibuat pada request yang sama, anggap basi
        }

        if ($lastUpdatedAt !== null && filemtime($latest) < strtotime($lastUpdatedAt)) {
            return null; // data berubah setelah PDF dibuat, perlu regenerasi
        }

        return $latest;
    }

    /**
     * Konversi docx ke PDF menggunakan LibreOffice headless.
     * Profil user LibreOffice dibuat unik per request agar konversi paralel
     * tidak saling mengunci (lock profil).
     *
     * Catatan Windows: harus pakai soffice.com (bukan soffice.exe) karena
     * soffice.exe langsung mengembalikan kendali sebelum konversi selesai.
     * Path profil harus URI valid (file:/// dengan forward slash); kalau tidak,
     * LibreOffice menampilkan dialog GUI "bootstrap.ini is corrupt" yang menumpuk.
     * Argumen dikirim sebagai array (tanpa shell) agar aman dari masalah quoting.
     */
    public static function convertDocxToPdf(string $docxPath, string $outDir, string $uniqueCode): void
    {
        $profileDir = sys_get_temp_dir() . '/lo_profile_' . $uniqueCode;

        // URI profil valid untuk Windows maupun Linux
        $profileUri = 'file:///' . str_replace('\\', '/', $profileDir);

        if (DIRECTORY_SEPARATOR === '\\') {
            $soffice = 'C:\\Program Files\\LibreOffice\\program\\soffice.com';
            if (! file_exists($soffice)) {
                $soffice = 'soffice.com'; // fallback jika ada di PATH
            }
        } else {
            $soffice = 'soffice';
        }

        // Jalankan sinkron (menunggu konversi selesai); seluruh perintah dibungkus
        // kutip agar path dengan spasi aman dilewatkan ke cmd.exe / sh.
        // Windows: kutip ganda polos (cmd.exe); Linux: escapeshellarg (single quote)
        $quote = fn (string $p) => DIRECTORY_SEPARATOR === '\\' ? '"' . $p . '"' : escapeshellarg($p);

        $command = '"' . $soffice . '"' .
            ' -env:UserInstallation=' . $profileUri .
            ' --headless --norestore --convert-to pdf' .
            ' --outdir ' . $quote($outDir) .
            ' ' . $quote($docxPath);
        exec($command . ' 2>&1');

        // Bersihkan profil sementara LibreOffice
        if (is_dir($profileDir)) {
            File::deleteDirectory($profileDir);
        }
    }
}
