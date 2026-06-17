<?php
// app/Providers/AppServiceProvider.php

namespace App\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Schedule $schedule): void
    {
        if (config('app.env') !== 'local' || str_contains(request()->getHost(), 'ngrok-free.app')) {
            URL::forceScheme('https');
        }
        // Schedule the deadline reminder command to run daily at 08:00
        $schedule->command('deadline:send-reminders')
            ->dailyAt('08:00')
            ->name('Send Deadline Reminders')
            ->description('Send email reminders 7 days before deadline');

        // Blade directive untuk check role
        Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->Role_User === $role;
        });

        // Blade directive untuk check multiple roles
        Blade::if('hasAnyRole', function (...$roles) {
            if (! auth()->check()) {
                return false;
            }

            foreach ($roles as $role) {
                if (auth()->user()->Role_User === $role) {
                    return true;
                }
            }

            return false;
        });

        // Blade directive untuk Admin
        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->Role_User === 'Admin';
        });

        // Blade directive untuk Dosen
        Blade::if('dosen', function () {
            return auth()->check() && auth()->user()->Role_User === 'Dosen';
        });

        // Blade directive untuk Mahasiswa
        Blade::if('mahasiswa', function () {
            return auth()->check() &&
                   (auth()->user()->Role_User === 'Mahasiswa' ||
                    auth()->user()->Role_User === 'mahasiswa');
        });

        // Blade directive untuk Laboran
        Blade::if('laboran', function () {
            return auth()->check() && auth()->user()->Role_User === 'Laboran';
        });
    }
}

/*
==============================================================
CARA PENGGUNAAN DI BLADE VIEWS:
==============================================================

1. Check single role:
   @role('Laboran')
       <p>Ini hanya tampil untuk Laboran</p>
   @endrole

2. Check multiple roles:
   @hasAnyRole('Admin', 'Laboran')
       <p>Ini tampil untuk Admin atau Laboran</p>
   @endhasAnyRole

3. Specific role shortcuts:
   @admin
       <p>Hanya Admin</p>
   @endadmin

   @dosen
       <p>Hanya Dosen</p>
   @enddosen

   @mahasiswa
       <p>Hanya Mahasiswa</p>
   @endmahasiswa

   @laboran
       <p>Hanya Laboran</p>
   @endlaboran

4. Kombinasi dengan @auth:
   @auth
       @role('Laboran')
           <a href="{{ route('laboran.dashboard') }}">Dashboard Laboran</a>
       @endrole
   @endauth

==============================================================
CONTOH IMPLEMENTASI NAVBAR:
==============================================================
*/
?>


<!-- <nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <h1 class="text-xl font-bold text-blue-600">RegLab System</h1>
            </div>
            
            <div class="flex items-center space-x-4">
                @auth
                    <span class="text-gray-700">{{ Auth::user()->Nama }}</span>
                    
                    @admin
                        <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">
                            Dashboard Admin
                        </a>
                    @endadmin
                    
                    @dosen
                        <a href="{{ route('dosen.dashboard') }}" class="text-blue-600 hover:text-blue-800">
                            Dashboard Dosen
                        </a>
                    @enddosen
                    
                    @mahasiswa
                        <a href="{{ route('mahasiswa.lab') }}" class="text-blue-600 hover:text-blue-800">
                            Dashboard Mahasiswa
                        </a>
                    @endmahasiswa
                    
                    @laboran
                        <a href="{{ route('laboran.dashboard') }}" class="text-blue-600 hover:text-blue-800">
                            Dashboard Laboran
                        </a>
                    @endlaboran
                    
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav> -->

<?php
/*
==============================================================
HELPER FUNCTIONS (OPTIONAL)
Tambahkan di app/helpers.php (jangan lupa autoload di composer.json)
==============================================================
*/

if (! function_exists('current_user_role')) {
    function current_user_role()
    {
        return auth()->check() ? auth()->user()->Role_User : null;
    }
}

if (! function_exists('is_role')) {
    function is_role($role)
    {
        return auth()->check() && auth()->user()->Role_User === $role;
    }
}

if (! function_exists('is_laboran')) {
    function is_laboran()
    {
        return is_role('Laboran');
    }
}

if (! function_exists('is_admin')) {
    function is_admin()
    {
        return is_role('Admin');
    }
}

if (! function_exists('is_dosen')) {
    function is_dosen()
    {
        return is_role('Dosen');
    }
}

if (! function_exists('is_mahasiswa')) {
    function is_mahasiswa()
    {
        return is_role('Mahasiswa') || is_role('mahasiswa');
    }
}

/*
Untuk menggunakan helpers, tambahkan di composer.json:

"autoload": {
    "files": [
        "app/helpers.php"
    ],
    "psr-4": {
        "App\\": "app/"
    }
}

Lalu jalankan: composer dump-autoload
*/
?>