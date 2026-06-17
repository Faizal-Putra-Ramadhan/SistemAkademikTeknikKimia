<?php

namespace App\Helpers;

/**
 * Security Helper untuk validasi dan sanitasi input
 * Digunakan di seluruh aplikasi untuk konsistensi
 */
class SecurityHelper
{
    /**
     * Validation rules untuk berbagai tipe input
     */
    public static function validationRules(): array
    {
        return [
            'nama' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s\'.,-]+$/u',
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^[\d\s\+\-\(\)]+$/',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&].+$/',
            ],
            'nomor_identitas' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9\-\.]+$/',
            ],
            'role' => [
                'required',
                'in:Admin,Dosen,Tendik,Mahasiswa,Safety Officer,Kepala Laboratorium,Laboran,Kaprodi,Peneliti Eksternal',
            ],
        ];
    }

    /**
     * Validation messages dalam bahasa Indonesia
     */
    public static function validationMessages(): array
    {
        return [
            'nama.required' => 'Nama wajib diisi',
            'nama.regex' => 'Nama hanya boleh mengandung huruf, spasi, dan tanda baca umum',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'phone.required' => 'Nomor telepon wajib diisi',
            'phone.regex' => 'Format nomor telepon tidak valid',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.regex' => 'Password harus mengandung: 1 huruf kecil dan 1 angka',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role wajib dipilih',
            'role.in' => 'Role tidak valid',
            'nomor_identitas.regex' => 'Nomor identitas hanya boleh berisi huruf, angka, titik, dan tanda hubung',
        ];
    }

    /**
     * Sanitize nama
     */
    public static function sanitizeName(?string $name): ?string
    {
        if (empty($name)) {
            return null;
        }

        // Remove tags, trim, remove multiple spaces
        $sanitized = trim(strip_tags($name));
        $sanitized = preg_replace('/\s+/', ' ', $sanitized);

        return $sanitized;
    }

    /**
     * Sanitize email
     */
    public static function sanitizeEmail(?string $email): ?string
    {
        if (empty($email)) {
            return null;
        }

        // Remove tags, trim, lowercase
        $sanitized = strtolower(trim(strip_tags($email)));

        return filter_var($sanitized, FILTER_SANITIZE_EMAIL);
    }

    /**
     * Sanitize phone number
     */
    public static function sanitizePhone(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        // Remove tags, trim
        $sanitized = trim(strip_tags($phone));

        // Keep only allowed characters
        $sanitized = preg_replace('/[^\d\s\+\-\(\)]/', '', $sanitized);

        return $sanitized;
    }

    /**
     * Sanitize nomor identitas (NIM/NIY)
     */
    public static function sanitizeIdentityNumber(?string $number): ?string
    {
        if (empty($number)) {
            return null;
        }

        // Remove tags, trim, uppercase
        $sanitized = strtoupper(trim(strip_tags($number)));

        // Keep only alphanumeric, dash, dot
        $sanitized = preg_replace('/[^a-zA-Z0-9\-\.]/', '', $sanitized);

        return $sanitized;
    }

    /**
     * Sanitize general string input
     */
    public static function sanitizeString(?string $input): ?string
    {
        if (empty($input)) {
            return null;
        }

        return trim(strip_tags($input));
    }

    /**
     * Daftar password yang umum dan harus ditolak
     */
    public static function commonPasswords(): array
    {
        return [
            'password', '12345678', 'qwerty', 'abc123', 'Password1',
            'Password1!', 'Welcome1!', 'Admin123!', 'Letmein1!',
            'qwerty123', 'abc12345', 'password123', 'Password123!',
            'welcome', 'admin123', 'letmein', 'monkey', 'dragon',
            '123456789', 'iloveyou', 'sunshine', 'princess', 'starwars',
        ];
    }

    /**
     * Check if password is too common
     */
    public static function isCommonPassword(string $password): bool
    {
        return in_array(strtolower($password), array_map('strtolower', self::commonPasswords()));
    }

    /**
     * Check if password contains part of email
     */
    public static function passwordContainsEmail(string $password, string $email): bool
    {
        $emailPrefix = explode('@', $email)[0];

        return stripos($password, $emailPrefix) !== false;
    }

    /**
     * Check if password contains part of name
     */
    public static function passwordContainsName(string $password, string $name): bool
    {
        $nameWords = explode(' ', $name);
        foreach ($nameWords as $word) {
            if (strlen($word) > 3 && stripos($password, $word) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validate password complexity
     */
    public static function validatePasswordComplexity(string $password): array
    {
        $errors = [];

        if (strlen($password) < 8) {
            $errors[] = 'Password minimal 8 karakter';
        }

        if (! preg_match('/[a-z]/', $password)) {
            $errors[] = 'Password harus mengandung minimal 1 huruf kecil';
        }

        if (! preg_match('/\d/', $password)) {
            $errors[] = 'Password harus mengandung minimal 1 angka';
        }

        return $errors;
    }

    /**
     * Generate secure random string
     */
    public static function generateSecureRandom(int $length = 32): string
    {
        return bin2hex(random_bytes($length / 2));
    }

    /**
     * Validate and sanitize file upload
     */
    public static function validateFileUpload($file, array $allowedExtensions = [], int $maxSizeKB = 2048): array
    {
        $errors = [];

        if (! $file) {
            $errors[] = 'File tidak ditemukan';

            return $errors;
        }

        if (! $file->isValid()) {
            $errors[] = 'File tidak valid';

            return $errors;
        }

        // Check file size
        if ($file->getSize() > ($maxSizeKB * 1024)) {
            $errors[] = "Ukuran file maksimal {$maxSizeKB}KB";
        }

        // Check extension
        if (! empty($allowedExtensions)) {
            $extension = strtolower($file->getClientOriginalExtension());
            if (! in_array($extension, $allowedExtensions)) {
                $errors[] = 'Format file tidak diizinkan. Format yang diizinkan: '.implode(', ', $allowedExtensions);
            }
        }

        return $errors;
    }

    /**
     * Sanitize filename for safe storage
     */
    public static function sanitizeFilename(string $filename): string
    {
        // Remove path traversal attempts
        $filename = basename($filename);

        // Remove special characters except dot, dash, underscore
        $filename = preg_replace('/[^a-zA-Z0-9\.\-_]/', '_', $filename);

        // Prevent double extensions
        $parts = explode('.', $filename);
        if (count($parts) > 2) {
            $extension = array_pop($parts);
            $filename = implode('_', $parts).'.'.$extension;
        }

        return $filename;
    }

    /**
     * Get allowed roles
     */
    public static function allowedRoles(): array
    {
        return [
            'Admin',
            'Dosen',
            'Tendik',
            'Mahasiswa',
            'Safety Officer',
            'Kepala Laboratorium',
            'Laboran',
            'Kaprodi',
            'Peneliti Eksternal',
        ];
    }

    /**
     * Check if role is valid
     */
    public static function isValidRole(string $role): bool
    {
        return in_array($role, self::allowedRoles());
    }
}
