{{-- 
    Sidebar partial - role-aware navigation
    Used by layouts/app.blade.php
--}}
@php
    use Illuminate\Support\Facades\Auth;
    use App\Http\Controllers\LabSwitchController;
    $currentUser = Auth::user();
    $role = $currentUser->Role_User ?? '';
    
    // Get labs for roles that need it
    $labs = $labs ?? collect();
    
    // Untuk Laboran, gunakan lab aktif dari session
    if ($role === 'Laboran' && $currentUser) {
        $activeLab = LabSwitchController::getActiveLab($currentUser);
        $currentLab = $activeLab;
        $labId = $activeLab ? $activeLab->id : ($labs->first()->id ?? 1);
    } else {
        // Untuk role lain, gunakan logic lama
        $currentLab = $labs->first();
        if (request()->route('id')) {
            $currentLab = $labs->firstWhere('id', request()->route('id')) ?? $currentLab;
        }
        $labId = $currentLab->id ?? ($labs->first()->id ?? 1);
    }
@endphp

<aside class="app-sidebar" id="appSidebar">
    {{-- Brand --}}
    <div class="sidebar-brand">
        <img src="{{ asset('logo/Logo-UAD-Berwarna.png') }}" alt="UAD">
        <div class="sidebar-brand-text">
            Lab Tekkim UAD
            <small>Sistem Laboratorium</small>
        </div>
    </div>

    {{-- Menu --}}
    <nav class="sidebar-menu">
        {{-- Mobile Controls (Lab & Role Switcher) --}}
        <div class="show-mobile px-3 mb-4">
            @php
                $labsForLaboran = collect();
                if ($role === 'Laboran' && $currentUser) {
                    $labsForLaboran = App\Http\Controllers\LabSwitchController::getLabsForLaboran($currentUser);
                }
            @endphp

            @if($role === 'Laboran' && $labsForLaboran->count() > 1)
                <div class="sidebar-label px-0 !pt-0">Ganti Lab</div>
                <select onchange="switchLab(this.value)" 
                        style="width: 100%; padding: 8px 12px; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; background: rgba(255,255,255,0.05); color: #fff; font-size: 13px; cursor: pointer; margin-bottom: 16px;">
                    @foreach($labsForLaboran as $lab)
                        <option value="{{ $lab->id }}" {{ $currentLab && $currentLab->id == $lab->id ? 'selected' : '' }} style="background: var(--color-sidebar); color: #fff;">
                            🧪 {{ $lab->Nama_Laboratorium }}
                        </option>
                    @endforeach
                </select>
            @endif

            <div style="border-bottom: 1px solid rgba(255,255,255,0.08); margin: 20px 0;"></div>
        </div>

        <div class="sidebar-label">Menu Utama</div>

        {{-- ==================== LABORAN ==================== --}}
        @if($role === 'Laboran')
            <a href="{{ route('laboran.dashboard', $labId) }}" 
               class="sidebar-link {{ request()->routeIs('laboran.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                <span>Dashboard</span>
            </a>

            <div class="sidebar-label">Peminjaman</div>

            <a href="{{ route('laboran.peminjaman-ruangan', $labId) }}" 
               class="sidebar-link {{ request()->routeIs('laboran.peminjaman-ruangan') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819"/></svg>
                <span>Peminjaman Ruangan</span>
            </a>

            <a href="{{ route('laboran.peminjaman-alat', $labId) }}" 
               class="sidebar-link {{ request()->routeIs('laboran.peminjaman-alat') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.25 5.25a2.121 2.121 0 01-3-3l5.25-5.25m9.88-2.12l-5.25 5.25a2.121 2.121 0 01-3-3l5.25-5.25m1.06-1.06a3 3 0 00-4.24 0l-1.06 1.06m4.24-4.24a3 3 0 014.24 4.24l-1.06 1.06"/></svg>
                <span>Peminjaman Alat</span>
            </a>

            <div class="sidebar-label">Kelola</div>

            <a href="{{ route('laboran.bebas-lab', $labId) }}" 
               class="sidebar-link {{ request()->routeIs('laboran.bebas-lab*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Bebas Lab</span>
            </a>

            <a href="{{ route('laboran.risk-assessment', $labId) }}" 
               class="sidebar-link {{ request()->routeIs('laboran.risk-assessment*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                <span>Risk Assessment</span>
            </a>

            <a href="{{ route('laboran.alat.index', $labId) }}" 
               class="sidebar-link {{ request()->routeIs('laboran.alat.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                <span>Kelola Alat</span>
            </a>

            <a href="{{ route('laboran.pengumuman', $labId) }}" 
               class="sidebar-link {{ request()->routeIs('laboran.pengumuman*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 001.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 010 3.46"/></svg>
                <span>Kelola Pengumuman</span>
            </a>

            <div class="sidebar-label">Akun</div>

            <a href="{{ route('laboran.profil') }}" 
               class="sidebar-link {{ request()->routeIs('laboran.profil*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                <span>Profil</span>
            </a>

        {{-- ==================== KEPALA LABORATORIUM ==================== --}}
        @elseif($role === 'Kepala Laboratorium')
            <a href="{{ route('kepala-lab.dashboard', $labId) }}" 
               class="sidebar-link {{ request()->routeIs('kepala-lab.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                <span>Dashboard</span>
            </a>

            <div class="sidebar-label">Kelola</div>

            <a href="{{ route('kepala-lab.risk-assessment.index', $labId) }}" 
               class="sidebar-link {{ request()->routeIs('kepala-lab.risk-assessment.index') || request()->routeIs('kepala-lab.risk-assessment.show') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                <span>Risk Assessment</span>
            </a>

            <a href="{{ route('kepala-lab.risk-assessment.report', $labId) }}" 
               class="sidebar-link {{ request()->routeIs('kepala-lab.risk-assessment.report') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                <span>Laporan RA</span>
            </a>

      

            <a href="{{ route('kepala-lab.peminjaman-ruangan.index') }}" 
               class="sidebar-link {{ request()->routeIs('kepala-lab.peminjaman-ruangan.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819"/></svg>
                <span>Peminjaman Ruangan</span>
            </a>

            <a href="{{ route('kepala-lab.pengumuman.index', $labId) }}" 
               class="sidebar-link {{ request()->routeIs('kepala-lab.pengumuman.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 001.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 010 3.46"/></svg>
                <span>Kelola Pengumuman</span>
            </a>

            <div class="sidebar-label">Akun</div>

            <a href="{{ route('kepala-lab.profil') }}"
               class="sidebar-link {{ request()->routeIs('kepala-lab.profil*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                <span>Profil</span>
            </a>

        {{-- ==================== MAHASISWA ==================== --}}
        @elseif($role === 'Mahasiswa')
            <a href="{{ route('mahasiswa.dashboard', $labId) }}" 
               class="sidebar-link {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                <span>Dashboard</span>
            </a>

            <div class="sidebar-label">Layanan</div>

            <a href="{{ route('mahasiswa.pinjam-ruangan', $labId) }}" 
               class="sidebar-link {{ request()->routeIs('mahasiswa.pinjam-ruangan') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819"/></svg>
                <span>Pinjam Ruangan</span>
            </a>

            @php
                $pinjamAlatId = $labId;
                if (isset($lab) && $lab->lab_type !== 'penelitian') {
                    $researchLab = \App\Models\DaftarLab::where('lab_type', 'penelitian')->first();
                    if ($researchLab) {
                        $pinjamAlatId = $researchLab->id;
                    }
                }
            @endphp
            <a href="{{ route('mahasiswa.pinjam-alat', $pinjamAlatId) }}" 
               class="sidebar-link {{ request()->routeIs('mahasiswa.pinjam-alat') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.25 5.25a2.121 2.121 0 01-3-3l5.25-5.25m9.88-2.12l-5.25 5.25a2.121 2.121 0 01-3-3l5.25-5.25m1.06-1.06a3 3 0 00-4.24 0l-1.06 1.06m4.24-4.24a3 3 0 014.24 4.24l-1.06 1.06"/></svg>
                <span>Pinjam Alat</span>
            </a>

            <a href="{{ route('mahasiswa.risk-assessment.index') }}" 
               class="sidebar-link {{ request()->routeIs('mahasiswa.risk-assessment.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                <span>Risk Assessment</span>
            </a>

            <a href="{{ route('mahasiswa.bebas-lab.index') }}" 
               class="sidebar-link {{ request()->routeIs('mahasiswa.bebas-lab.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Bebas Lab</span>
            </a>

            <a href="{{ route('mahasiswa.aktivitas', $labId) }}" 
               class="sidebar-link {{ request()->routeIs('mahasiswa.aktivitas') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Lihat Aktivitas</span>
            </a>

            <div class="sidebar-label">Akun</div>

            <a href="{{ route('mahasiswa.profil', $labId) }}"
               class="sidebar-link {{ request()->routeIs('mahasiswa.profil') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                <span>Profil</span>
            </a>

        {{-- ==================== DOSEN ==================== --}}
        @elseif($role === 'Dosen')
            <a href="{{ route('dosen.dashboard', $labId) }}" 
               class="sidebar-link {{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                <span>Dashboard</span>
            </a>

            <div class="sidebar-label">Layanan</div>

            <a href="{{ route('dosen.pinjam-ruangan', $labId) }}" 
               class="sidebar-link {{ request()->routeIs('dosen.pinjam-ruangan') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819"/></svg>
                <span>Pinjam Ruangan</span>
            </a>

            <a href="{{ route('dosen.pinjam-alat', $labId) }}" 
               class="sidebar-link {{ request()->routeIs('dosen.pinjam-alat') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.25 5.25a2.121 2.121 0 01-3-3l5.25-5.25m9.88-2.12l-5.25 5.25a2.121 2.121 0 01-3-3l5.25-5.25m1.06-1.06a3 3 0 00-4.24 0l-1.06 1.06m4.24-4.24a3 3 0 014.24 4.24l-1.06 1.06"/></svg>
                <span>Pinjam Alat</span>
            </a>

            <a href="{{ route('dosen.pengumuman.index', $labId) }}" 
               class="sidebar-link {{ request()->routeIs('dosen.pengumuman.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 001.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 010 3.46"/></svg>
                <span>Pengumuman</span>
            </a>

            <a href="{{ route('dosen.risk-assessment.index', $labId) }}" 
               class="sidebar-link {{ request()->routeIs('dosen.risk-assessment.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                <span>Risk Assessment</span>
            </a>

            <div class="sidebar-label">Akun</div>

            <a href="{{ route('dosen.profil', $labId) }}"
               class="sidebar-link {{ request()->routeIs('dosen.profil') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                <span>Profil</span>
            </a>

        {{-- ==================== SAFETY OFFICER ==================== --}}
        @elseif($role === 'Safety Officer')
            <a href="{{ route('safety-officer.dashboard') }}" 
               class="sidebar-link {{ request()->routeIs('safety-officer.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                <span>Dashboard</span>
            </a>

            <div class="sidebar-label">Risk Assessment</div>

            <a href="{{ route('safety-officer.risk-assessment.index') }}" 
               class="sidebar-link {{ request()->routeIs('safety-officer.risk-assessment.index') || request()->routeIs('safety-officer.risk-assessment.show') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                <span>Review RA</span>
            </a>

            <a href="{{ route('safety-officer.risk-assessment.schedules') }}" 
               class="sidebar-link {{ request()->routeIs('safety-officer.risk-assessment.schedules') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                <span>Kelola Jadwal</span>
            </a>

            <a href="{{ route('safety-officer.pengumuman.index') }}" 
               class="sidebar-link {{ request()->routeIs('safety-officer.pengumuman.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 001.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 010 3.46"/></svg>
                <span>Pengumuman</span>
            </a>

            <div class="sidebar-label">Akun</div>

            <a href="{{ route('safety-officer.profil') }}"
               class="sidebar-link {{ request()->routeIs('safety-officer.profil') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                <span>Profil</span>
            </a>

        {{-- ==================== KAPRODI ==================== --}}
        @elseif($role === 'Kaprodi')
            <a href="{{ route('kaprodi.dashboard') }}" 
               class="sidebar-link {{ request()->routeIs('kaprodi.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                <span>Dashboard</span>
            </a>

            <div class="sidebar-label">Risk Assessment</div>

            <a href="{{ route('kaprodi.risk-assessment.index') }}" 
               class="sidebar-link {{ request()->routeIs('kaprodi.risk-assessment.index') || request()->routeIs('kaprodi.risk-assessment.review') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                <span>Persetujuan RA</span>
            </a>

            <a href="{{ route('kaprodi.perpanjangan.index') }}" 
               class="sidebar-link {{ request()->routeIs('kaprodi.perpanjangan.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
                <span>Perpanjangan RA</span>
            </a>

            <a href="{{ route('kaprodi.risk-assessment.report') }}" 
               class="sidebar-link {{ request()->routeIs('kaprodi.risk-assessment.report') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                <span>Laporan</span>
            </a>

            <div class="sidebar-label">Kelola</div>

            <a href="{{ route('kaprodi.peminjaman-ruangan.index') }}" 
               class="sidebar-link {{ request()->routeIs('kaprodi.peminjaman-ruangan.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819"/></svg>
                <span>Peminjaman Ruangan</span>
            </a>

            <a href="{{ route('kaprodi.pengumuman.index') }}" 
               class="sidebar-link {{ request()->routeIs('kaprodi.pengumuman.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 001.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 010 3.46"/></svg>
                <span>Kelola Pengumuman</span>
            </a>

            <div class="sidebar-label">Akun</div>

            <a href="{{ route('kaprodi.profil') }}"
               class="sidebar-link {{ request()->routeIs('kaprodi.profil') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                <span>Profil</span>
            </a>

        {{-- ==================== PENELITI EKSTERNAL ==================== --}}
        @elseif($role === 'Peneliti Eksternal')
            <a href="{{ route('peneliti-eksternal.dashboard', $labId) }}" 
               class="sidebar-link {{ request()->routeIs('peneliti-eksternal.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                <span>Dashboard</span>
            </a>

            <div class="sidebar-label">Layanan</div>

            <a href="{{ route('peneliti-eksternal.pinjam-ruangan', $labId) }}" 
               class="sidebar-link {{ request()->routeIs('peneliti-eksternal.pinjam-ruangan') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819"/></svg>
                <span>Pinjam Ruangan</span>
            </a>

            <a href="{{ route('peneliti-eksternal.pinjam-alat', $labId) }}" 
               class="sidebar-link {{ request()->routeIs('peneliti-eksternal.pinjam-alat') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.25 5.25a2.121 2.121 0 01-3-3l5.25-5.25m9.88-2.12l-5.25 5.25a2.121 2.121 0 01-3-3l5.25-5.25m1.06-1.06a3 3 0 00-4.24 0l-1.06 1.06m4.24-4.24a3 3 0 014.24 4.24l-1.06 1.06"/></svg>
                <span>Pinjam Alat</span>
            </a>

            <a href="{{ route('peneliti-eksternal.risk-assessment.index') }}" 
               class="sidebar-link {{ request()->routeIs('peneliti-eksternal.risk-assessment.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                <span>Risk Assessment</span>
            </a>

            <a href="{{ route('peneliti-eksternal.aktivitas', $labId) }}" 
               class="sidebar-link {{ request()->routeIs('peneliti-eksternal.aktivitas') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Lihat Aktivitas</span>
            </a>

            <a href="{{ route('peneliti-eksternal.bebas-lab') }}" 
               class="sidebar-link {{ request()->routeIs('peneliti-eksternal.bebas-lab*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Bebas Lab</span>
            </a>

            <div class="sidebar-label">Akun</div>

            <a href="{{ route('peneliti-eksternal.profil', $labId) }}" 
               class="sidebar-link {{ request()->routeIs('peneliti-eksternal.profil') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                <span>Profil</span>
            </a>

        {{-- ==================== ADMIN ==================== --}}
        @elseif($role === 'Admin')
            <a href="{{ route('admin.dashboard') }}" 
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                <span>Dashboard</span>
            </a>

            <div class="sidebar-label">Manajemen</div>

            <a href="{{ route('admin.tambah-user.index') }}" 
               class="sidebar-link {{ request()->routeIs('admin.tambah-user.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/></svg>
                <span>Registrasi</span>
            </a>

            <a href="{{ route('admin.kelola-user.index') }}" 
               class="sidebar-link {{ request()->routeIs('admin.kelola-user.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                <span>Kelola User</span>
            </a>

            <a href="{{ route('admin.tambah-laboran.index') }}" 
               class="sidebar-link {{ request()->routeIs('admin.tambah-laboran.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Tambah Laboran</span>
            </a>

            <a href="{{ route('admin.daftar-lab.index') }}" 
               class="sidebar-link {{ request()->routeIs('admin.daftar-lab.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819"/></svg>
                <span>Daftar Laboratorium</span>
            </a>

            <a href="{{ route('admin.alat-lab.index') }}" 
               class="sidebar-link {{ request()->routeIs('admin.alat-lab.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                <span>Kelola Alat Lab</span>
            </a>

            <a href="{{ route('admin.pengumuman.index') }}" 
               class="sidebar-link {{ request()->routeIs('admin.pengumuman.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 001.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 010 3.46"/></svg>
                <span>Kelola Pengumuman</span>
            </a>

            <a href="{{ route('admin.templates.index') }}" 
               class="sidebar-link {{ request()->routeIs('admin.templates.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                <span>Kelola Template</span>
            </a>

            <a href="{{ route('admin.aktivitas-administrator') }}" 
               class="sidebar-link {{ request()->routeIs('admin.aktivitas-administrator') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Aktivitas Admin</span>
            </a>

            <div class="sidebar-label">Akun</div>

            <a href="{{ route('admin.profile.edit') }}" 
               class="sidebar-link {{ request()->routeIs('admin.profile.edit') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                <span>Ubah Profil</span>
            </a>
        @endif
    </nav>

    {{-- Footer / Logout --}}
    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</aside>
