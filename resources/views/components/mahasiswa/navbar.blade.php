{{-- 
    File: resources/views/components/mahasiswa/navbar.blade.php
    FIX: Menambahkan null safety untuk $user dan $currentLab
--}}

@props(['labs', 'user'])

@php
    use Illuminate\Support\Facades\Auth;
    
    // FIX: Pastikan $user tidak null
    $currentUser = $user ?? Auth::user();
    
    // FIX: Handle $labs dengan aman
    $labsCollection = collect($labs ?? []);
    $currentLab = $labsCollection->first();

    if (request()->route('id')) {
        $currentLab = $labsCollection->firstWhere('id', request()->route('id')) ?? $currentLab;
    }

    // FIX: NULL SAFE untuk labId
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
              
              {{-- FIX: Pastikan $labId tidak null --}}
              <x-mahasiswa.nav-link href="{{ route('mahasiswa.dashboard', $labId) }}" 
              :active="request()->routeIs('mahasiswa.dashboard')">Dashboard</x-mahasiswa.nav-link>

              <x-mahasiswa.nav-link href="{{ route('mahasiswa.pinjam-ruangan', $labId) }}"
              :active="request()->routeIs('mahasiswa.pinjam-ruangan')">Pinjam Ruangan</x-mahasiswa.nav-link>

              <x-mahasiswa.nav-link href="{{ route('mahasiswa.pinjam-alat', $labId) }}"
              :active="request()->routeIs('mahasiswa.pinjam-alat')">Pinjam Alat</x-mahasiswa.nav-link>          

              <x-mahasiswa.nav-link href="{{ route('mahasiswa.risk-assessment.index') }}"
              :active="request()->routeIs('mahasiswa.risk-assessment.*')">Risk Assessment</x-mahasiswa.nav-link>

              <x-mahasiswa.nav-link href="{{ route('mahasiswa.bebas-lab.index') }}"
              :active="request()->routeIs('mahasiswa.bebas-lab.*')">Bebas Lab</x-mahasiswa.nav-link>

              <x-mahasiswa.nav-link href="{{ route('mahasiswa.aktivitas', $labId) }}"
              :active="request()->routeIs('mahasiswa.aktivitas')">Lihat Aktivitas</x-mahasiswa.nav-link>

            </div>
          </div>
        </div>
        <div class="hidden md:block">
          <div class="ml-4 flex items-center md:ml-6">
            <!-- Role Switcher Component - HIDDEN untuk Mahasiswa -->
            {{-- <x-role-switcher :user="$user" /> --}}

            <button type="button" class="relative rounded-full p-1 text-gray-400 hover:text-white focus:outline-2 focus:outline-offset-2 focus:outline-indigo-500">
              <!-- Notifications placeholder -->
            </button>

            {{-- FIX: Tambahkan null check untuk $currentUser --}}
            @if($currentUser)
            <a href="{{ route('mahasiswa.profil', $labId) }}"
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
                
                {{-- FIX: Handle foto yang mungkin null --}}
                @if($currentUser->foto && file_exists(public_path('uploads/profile/' . $currentUser->foto)))
                    <img src="{{ asset('uploads/profile/' . $currentUser->foto) }}" alt="" class="size-8 rounded-full outline -outline-offset-1 outline-white/10" />
                @else
                    <div class="size-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold outline -outline-offset-1 outline-white/10">
                        {{ substr($currentUser->Nama, 0, 1) }}
                    </div>
                @endif
              </button>

              <el-menu anchor="bottom end" popover class="w-48 origin-top-right rounded-md bg-gray-800 py-1 outline-1 -outline-offset-1 outline-white/10 transition transition-discrete [--anchor-gap:--spacing(2)] data-closed:scale-95 data-closed:transform data-closed:opacity-0 data-enter:duration-100 data-enter:ease-out data-leave:duration-75 data-leave:ease-in">
                <a href="{{ route('mahasiswa.profil', $labId) }}" class="block px-4 py-2 text-sm text-gray-300 focus:bg-white/5 focus:outline-hidden">Your profile</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="block w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-white/5 hover:text-white">
                        Sign out
                    </button>
                </form>
              </el-menu>
            </el-dropdown>
            @else
            {{-- Fallback jika user null --}}
            <a href="{{ route('login') }}" class="text-gray-300 hover:text-white px-3 py-2">Login</a>
            @endif
          </div>
        </div>
        <div class="-mr-2 flex md:hidden">
          <!-- Mobile menu button -->
          <button type="button" command="--toggle" commandfor="mobile-menu" class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-white/5 hover:text-white focus:outline-2 focus:outline-offset-2 focus:outline-indigo-500">
            <span class="absolute -inset-0.5"></span>
            <span class="sr-only">Open main menu</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6 in-aria-expanded:hidden">
              <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6 not-in-aria-expanded:hidden">
              <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <el-disclosure id="mobile-menu" hidden class="block md:hidden">
      <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
        
        <x-mahasiswa.nav-link-mob href="{{ route('mahasiswa.dashboard', $labId) }}" 
        :active="request()->routeIs('mahasiswa.dashboard')">Dashboard</x-mahasiswa.nav-link-mob>

        <x-mahasiswa.nav-link-mob href="{{ route('mahasiswa.pinjam-ruangan', $labId) }}" 
        :active="request()->routeIs('mahasiswa.pinjam-ruangan')">Pinjam Ruangan</x-mahasiswa.nav-link-mob>

        <x-mahasiswa.nav-link-mob href="{{ route('mahasiswa.pinjam-alat', $labId) }}"
        :active="request()->routeIs('mahasiswa.pinjam-alat')">Pinjam Alat</x-mahasiswa.nav-link-mob>

        <x-mahasiswa.nav-link-mob href="{{ route('mahasiswa.risk-assessment.index') }}" 
        :active="request()->routeIs('mahasiswa.risk-assessment.*')">Risk Assessment</x-mahasiswa.nav-link-mob>

        <x-mahasiswa.nav-link-mob href="{{ route('mahasiswa.bebas-lab.index') }}" 
        :active="request()->routeIs('mahasiswa.bebas-lab.*')">Bebas Lab</x-mahasiswa.nav-link-mob>

        <x-mahasiswa.nav-link-mob href="{{ route('mahasiswa.aktivitas', $labId) }}" 
        :active="request()->routeIs('mahasiswa.aktivitas')">Lihat Aktivitas</x-mahasiswa.nav-link-mob>
          
      </div>
      
      {{-- FIX: Tambahkan null check untuk mobile menu --}}
      @if($currentUser)
      <div class="border-t border-white/10 pt-4 pb-3">
        <div class="flex items-center px-5">
          <div class="shrink-0">
            {{-- FIX: Handle foto yang mungkin null --}}
            @if($currentUser->foto && file_exists(public_path('uploads/profile/' . $currentUser->foto)))
                <img src="{{ asset('uploads/profile/' . $currentUser->foto) }}" alt="" class="size-10 rounded-full outline -outline-offset-1 outline-white/10" />
            @else
                <div class="size-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold outline -outline-offset-1 outline-white/10">
                    {{ substr($currentUser->Nama, 0, 1) }}
                </div>
            @endif
          </div>
          <div class="ml-3">
            <div class="text-base/5 font-medium text-white">{{ $currentUser->Nama }}</div>
            <div class="text-sm font-medium text-gray-400">{{ $currentUser->Email }}</div>
          </div>
          <button type="button" class="relative ml-auto shrink-0 rounded-full p-1 text-gray-400 hover:text-white focus:outline-2 focus:outline-offset-2 focus:outline-indigo-500">
            <!-- Notifications placeholder -->
          </button>
        </div>
        <div class="mt-3 space-y-1 px-2">
          {{-- Role Switcher Component - HIDDEN untuk Mahasiswa --}}
          {{-- <x-role-switcher :user="$currentUser" /> --}}
          <a href="{{ route('mahasiswa.profil', $labId) }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-white/5 hover:text-white">Your profile</a>
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="block w-full text-left rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-white/5 hover:text-white">
                Sign out
            </button>
          </form>
        </div>
      </div>
      @endif
    </el-disclosure>
  </nav>