<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Middleware untuk validasi password complexity
 */
class CheckSecurePassword
{
    /**
     * List common passwords yang harus ditolak
     */
    private $commonPasswords = [
        'password', '12345678', 'qwerty', 'abc123', 'Password1',
        'welcome', 'admin123', 'letmein', 'monkey', 'dragon',
        '123456789', 'iloveyou', 'sunshine', 'princess', 'starwars',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('password')) {
            $password = $request->input('password');

            // Check password complexity
            $validator = Validator::make(['password' => $password], [
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/^(?=.*[a-z])(?=.*\d).+$/',
                ],
            ], [
                'password.regex' => 'Password harus mengandung: 1 huruf kecil dan 1 angka',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput($request->except('password', 'password_confirmation'));
            }

            // Check common passwords
            if (in_array(strtolower($password), $this->commonPasswords)) {
                return redirect()->back()
                    ->withErrors(['password' => 'Password terlalu umum. Pilih password yang lebih kuat.'])
                    ->withInput($request->except('password', 'password_confirmation'));
            }

            // Check if password contains username/email
            if ($request->has('email')) {
                $emailPrefix = explode('@', $request->input('email'))[0];
                if (stripos($password, $emailPrefix) !== false) {
                    return redirect()->back()
                        ->withErrors(['password' => 'Password tidak boleh mengandung bagian dari email.'])
                        ->withInput($request->except('password', 'password_confirmation'));
                }
            }
        }

        return $next($request);
    }
}
