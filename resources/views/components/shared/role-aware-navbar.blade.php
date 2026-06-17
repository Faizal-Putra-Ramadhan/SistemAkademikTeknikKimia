{{-- Role-Aware Navbar Component --}}
@props(['labs', 'user', 'routePrefix'])

@php
    use Illuminate\Support\Facades\Auth;
    
    // Ensure $user is not null
    $currentUser = $user ?? Auth::user();
    
    // Handle $labs safely
    $labsCollection = collect($labs ?? []);
    $currentLab = $labsCollection->first();

    if (request()->route('id')) {
        $currentLab = $labsCollection->firstWhere('id', request()->route('id')) ?? $currentLab;
    }

    // Safe labId
    $labId = $currentLab?->id ?? ($labsCollection->first()?->id ?? 1);
@endphp

<nav class="bg-gray-800/100">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between">
        <div class="flex items-center">
          <div class="shrink-0">
            <img src="{{ asset('logo/Logo-UAD-Berwarna.png') }}" alt="Your Company" class="size-8" />
          </div>
          <div class="hidden md:block">
            <div class="ml-10 flex items-baseline space-x-4">
              
              {{-- Dashboard --}}
              <x-mahasiswa.nav-link href="{{ route($routePrefix . '.dashboard', $labId) }}" 
              :active="request()->routeIs($routePrefix . '.dashboard')">Dashboard</x-mahasiswa.nav-link>

              {{-- Pinjam Ruangan --}}
              <x-mahasiswa.nav-link href="{{ route($routePrefix . '.pinjam-ruangan', $labId) }}"
              :active="request()->routeIs($routePrefix . '.pinjam-ruangan')">Pinjam Ruangan</x-mahasiswa.nav-link>

              {{-- Pinjam Alat --}}
              <x-mahasiswa.nav-link href="{{ route($routePrefix . '.pinjam-alat', $labId) }}"
              :active="request()->routeIs($routePrefix . '.pinjam-alat')">Pinjam Alat</x-mahasiswa.nav-link>          

              {{-- Risk Assessment --}}
              <x-mahasiswa.nav-link href="{{ route($routePrefix . '.risk-assessment.index') }}"
              :active="request()->routeIs($routePrefix . '.risk-assessment.*')">Risk Assessment</x-mahasiswa.nav-link>

              {{-- Aktivitas --}}
              <x-mahasiswa.nav-link href="{{ route($routePrefix . '.aktivitas', $labId) }}"
              :active="request()->routeIs($routePrefix . '.aktivitas')">Lihat Aktivitas</x-mahasiswa.nav-link>

            </div>
          </div>
        </div>
        <div class="hidden md:block">
          <div class="ml-4 flex items-center md:ml-6">
            <button type="button" class="relative rounded-full p-1 text-gray-400 hover:text-white focus:outline-2 focus:outline-offset-2 focus:outline-indigo-500">
              <!-- Notifications placeholder -->
            </button>

            {{-- Profile Link --}}
            @if($currentUser)
            <a href="{{ route($routePrefix . '.profil', $labId) }}"
               class="flex items-center gap-2 px-3 py-1.5 rounded-md bg-white/10 text-gray-200 hover:bg-white/20 transition">

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" 
                     viewBox="0 0 24 24" stroke-width="1.5" 
                     stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 6.75a3.75 3.75 0 11-7.5 
                    0 3.75 3.75 0 017.5 0zM4.5 
                    20.25a8.25 8.25 0 1115 
                    0v.75H4.5v-.75z" />
                </svg>

                <span>Profile</span>
            </a>

            <!-- Profile dropdown -->
            <el-dropdown class="relative ml-3">
              <button class="relative flex max-w-xs items-center rounded-full focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                <span class="absolute -inset-1.5"></span>
                <span class="sr-only">Open user menu</span>
                
                {{-- Profile Photo --}}
                @if($currentUser->foto && file_exists(public_path('uploads/profile/' . $currentUser->foto)))
                    <img src="{{ asset('uploads/profile/' . $currentUser->foto) }}" alt="" class="size-8 rounded-full outline -outline-offset-1 outline-white/10" />
                @else
                    <div class="size-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold outline -outline-offset-1 outline-white/10">
                        {{ substr($currentUser->Nama, 0, 1) }}
                    </div>
                @endif
              </button>

              <el-menu anchor="bottom end" popover class="w-48 origin-top-right rounded-md bg-gray-800 py-1 outline-1 -outline-offset-1 outline-white/10 transition transition-discrete [--anchor-gap:--spacing(2)] data-closed:scale-95 data-closed:transform data-closed:opacity-0 data-enter:duration-100 data-enter:ease-out data-leave:duration-75 data-leave:ease-in">
                <a href="{{ route($routePrefix . '.profil', $labId) }}" class="block px-4 py-2 text-sm text-gray-300 focus:bg-white/5 focus:outline-hidden">Your profile</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-300 focus:bg-white/5 focus:outline-hidden">Sign out</button>
                </form>
              </el-menu>
            </el-dropdown>
            @endif
          </div>
        </div>
      </div>
    </div>
  </nav>
