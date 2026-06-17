@props(['user'])

@php
    use Illuminate\Support\Facades\Auth;
    $currentUser = $user ?? Auth::user();
@endphp

<nav class="bg-gray-900 border-b border-white/10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center">
                <div class="shrink-0">
                    <img src="{{ asset('logo/Logo-UAD-Berwarna.png') }}" alt="Logo UAD" class="h-9 w-auto" />
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <x-kaprodi.nav-link href="{{ route('kaprodi.dashboard') }}" 
                            :active="request()->routeIs('kaprodi.dashboard')">
                            Dashboard
                        </x-kaprodi.nav-link>

                        <div class="relative">
                            <button type="button" id="kelola-ra-toggle"
                                class="inline-flex items-center gap-2 rounded-md px-3 py-2 text-sm font-medium transition
                                {{ (request()->routeIs('kaprodi.risk-assessment.*') || request()->routeIs('kaprodi.perpanjangan.*')) && !request()->routeIs('kaprodi.risk-assessment.report')
                                    ? 'bg-gray-900 text-white'
                                    : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
                                aria-haspopup="true" aria-expanded="false">
                                Kelola RA
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 10.94l3.71-3.71a.75.75 0 1 1 1.06 1.06l-4.24 4.25a.75.75 0 0 1-1.06 0L5.21 8.29a.75.75 0 0 1 .02-1.08z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div id="kelola-ra-menu" class="absolute left-0 z-10 mt-2 hidden w-56 rounded-md bg-gray-800 shadow-lg ring-1 ring-black/5">
                                <a href="{{ route('kaprodi.risk-assessment.index') }}"
                                   class="block px-4 py-2 text-sm {{ request()->routeIs('kaprodi.risk-assessment.*') && !request()->routeIs('kaprodi.risk-assessment.report') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                    Persetujuan RA
                                </a>
                                <a href="{{ route('kaprodi.perpanjangan.index') }}"
                                   class="flex items-center justify-between px-4 py-2 text-sm {{ request()->routeIs('kaprodi.perpanjangan.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                    <span>Perpanjangan RA</span>
                                    @php
                                        $pendingCount = \App\Models\RiskAssessment::where('pengajuan_perpanjangan', true)
                                            ->whereNull('persetujuan_perpanjangan_kaprodi')
                                            ->count();
                                    @endphp
                                    @if($pendingCount > 0)
                                        <span class="inline-flex items-center rounded-full bg-red-500 px-2 py-1 text-xs font-medium text-white">
                                            {{ $pendingCount }}
                                        </span>
                                    @endif
                                </a>
                            </div>
                        </div>

                        {{-- Peminjaman Ruangan --}}
                        <a href="{{ route('kaprodi.peminjaman-ruangan.index') }}" 
                           class="{{ request()->routeIs('kaprodi.peminjaman-ruangan.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} 
                                  rounded-md px-3 py-2 text-sm font-medium inline-flex items-center gap-2">
                            🏢 Peminjaman Ruangan
                            @php
                                $pendingRuanganCount = \App\Models\PeminjamanRuangan::whereIn('status', ['disetujui_laboran', 'menunggu_kepala_lab'])
                                    ->count();
                            @endphp
                            @if($pendingRuanganCount > 0)
                                <span class="inline-flex items-center rounded-full bg-red-500 px-2 py-1 text-xs font-medium text-white">
                                    {{ $pendingRuanganCount }}
                                </span>
                            @endif
                        </a>

                        <x-kaprodi.nav-link href="{{ route('kaprodi.risk-assessment.report') }}" 
                            :active="request()->routeIs('kaprodi.risk-assessment.report')">
                            Laporan
                        </x-kaprodi.nav-link>

                        <x-kaprodi.nav-link href="{{ route('kaprodi.pengumuman.index') }}" 
                            :active="request()->routeIs('kaprodi.pengumuman.*')">
                            Pengumuman
                        </x-kaprodi.nav-link>

                        
                    </div>
                </div>
            </div>

            <div class="hidden md:block">
                <div class="ml-4 flex items-center md:ml-6">
                    @if($currentUser)
                        <div class="relative flex items-center gap-3">
                            <div class="text-right mr-2 hidden lg:block">
                                <div class="text-sm font-medium text-white">{{ $currentUser->Nama }}</div>
                                <div class="text-xs text-gray-400">Kepala Program Studi</div>
                            </div>

                            <!-- Role Switcher Component -->
                            <x-role-switcher :user="$currentUser" />

                            <el-dropdown class="relative ml-3">
              <button class="relative flex max-w-xs items-center rounded-full focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                <span class="absolute -inset-1.5"></span>
                <span class="sr-only">Open user menu</span>
                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="size-8 rounded-full outline -outline-offset-1 outline-white/10" />
              </button>

              <el-menu anchor="bottom end" popover class="w-48 origin-top-right rounded-md bg-gray-800 py-1 outline-1 -outline-offset-1 outline-white/10 transition transition-discrete [--anchor-gap:--spacing(2)] data-closed:scale-95 data-closed:transform data-closed:opacity-0 data-enter:duration-100 data-enter:ease-out data-leave:duration-75 data-leave:ease-in">
                <a href="{{ route('kaprodi.profil') }}" class="block px-4 py-2 text-sm text-gray-300 focus:bg-white/5 focus:outline-hidden">Your profile</a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-300 focus:bg-white/5 focus:outline-hidden">Settings</a>
                <form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit"
        class="block w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-white/5 hover:text-white">
        Sign out
    </button>
</form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-300 hover:text-white px-3 py-2 text-sm font-medium">Masuk</a>
                    @endif
                </div>
            </div>

            <div class="-mr-2 flex md:hidden">
                <button type="button" class="inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none" 
                        onclick="toggleMobileMenu()">
                    <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div class="md:hidden hidden" id="mobile-menu">
        <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
            <a href="{{ route('kaprodi.dashboard') }}" 
               class="{{ request()->routeIs('kaprodi.dashboard') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} 
                      block rounded-md px-3 py-2 text-base font-medium">
                Dashboard
            </a>

            <x-role-switcher :user="$currentUser" />

            <div class="rounded-md bg-gray-800/60 px-3 py-2">
                <div class="text-sm font-semibold text-white">Kelola RA</div>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('kaprodi.risk-assessment.index') }}" 
                       class="{{ request()->routeIs('kaprodi.risk-assessment.*') && !request()->routeIs('kaprodi.risk-assessment.report') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} 
                              block rounded-md px-3 py-2 text-base font-medium">
                        Persetujuan RA
                    </a>
                    <a href="{{ route('kaprodi.perpanjangan.index') }}" 
                       class="{{ request()->routeIs('kaprodi.perpanjangan.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} 
                              block rounded-md px-3 py-2 text-base font-medium">
                        Perpanjangan RA
                        @php
                            $pendingCount = \App\Models\RiskAssessment::where('pengajuan_perpanjangan', true)
                                ->whereNull('persetujuan_perpanjangan_kaprodi')
                                ->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="ml-2 inline-flex items-center rounded-full bg-red-500 px-2 py-1 text-xs font-medium text-white">
                                {{ $pendingCount }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>

            <a href="{{ route('kaprodi.peminjaman-ruangan.index') }}" 
               class="{{ request()->routeIs('kaprodi.peminjaman-ruangan.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} 
                      block rounded-md px-3 py-2 text-base font-medium">
                🏢 Peminjaman Ruangan
                @php
                    $pendingRuanganCount = \App\Models\PeminjamanRuangan::whereIn('status', ['disetujui_laboran', 'menunggu_kepala_lab'])->count();
                @endphp
                @if($pendingRuanganCount > 0)
                    <span class="ml-2 inline-flex items-center rounded-full bg-red-500 px-2 py-1 text-xs font-medium text-white">
                        {{ $pendingRuanganCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('kaprodi.risk-assessment.report') }}" 
               class="{{ request()->routeIs('kaprodi.risk-assessment.report') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} 
                      block rounded-md px-3 py-2 text-base font-medium">
                Laporan
            </a>

            <a href="{{ route('kaprodi.pengumuman.index') }}" 
               class="{{ request()->routeIs('kaprodi.pengumuman.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} 
                      block rounded-md px-3 py-2 text-base font-medium">
                Pengumuman
            </a>

            
        </div>
    </div>
</nav>

<script>
function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    menu.classList.toggle('hidden');
}

document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('kelola-ra-toggle');
    const menu = document.getElementById('kelola-ra-menu');
    if (!toggle || !menu) return;

    const closeMenu = () => {
        menu.classList.add('hidden');
        toggle.setAttribute('aria-expanded', 'false');
    };

    toggle.addEventListener('click', (e) => {
        e.stopPropagation();
        const isHidden = menu.classList.contains('hidden');
        menu.classList.toggle('hidden', !isHidden);
        toggle.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
    });

    document.addEventListener('click', closeMenu);
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeMenu();
    });
});
</script>