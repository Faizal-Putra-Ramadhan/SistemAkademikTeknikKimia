<div class="sidebar" id="sidebar">

    <a href="{{ route('admin.dashboard') }}"
       class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }} !no-underline text-white flex items-center gap-2"
       style="text-decoration: none !important; color: white;">
        <svg fill="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('admin.tambah-user.index') }}"
       class="menu-item {{ request()->routeIs('admin.tambah-user.*') ? 'active' : '' }} !no-underline text-white flex items-center gap-2"
       style="text-decoration: none !important; color: white;">
        <svg fill="currentColor" viewBox="0 0 24 24" class="w-5 h-5">
            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
        </svg>
        <span>Registrasi</span>
    </a>

    <a href="{{ route('admin.kelola-user.index') }}"
       class="menu-item {{ request()->routeIs('admin.kelola-user.*') ? 'active' : '' }} !no-underline text-white flex items-center gap-2"
       style="text-decoration: none !important; color: white;">
        <svg fill="currentColor" viewBox="0 0 24 24">
            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
        </svg>
        <span>Kelola User</span>
    </a>

    <a href="{{ route('tambah-laboran.index') }}"
       class="menu-item {{ request()->routeIs('tambah-laboran.*') ? 'active' : '' }} !no-underline text-white flex items-center gap-2"
       style="text-decoration: none !important; color: white;">
        <svg fill="currentColor" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="3"/>
            <path d="M19.4 15c-.3 0-.5-.1-.6-.4-.2-.3-.1-.7.2-.9 1.1-.8 1.8-2.1 1.8-3.5s-.7-2.7-1.8-3.5c-.3-.2-.4-.6-.2-.9.2-.3.6-.4.9-.2 1.5 1 2.4 2.7 2.4 4.6s-.9 3.6-2.4 4.6c-.1.1-.2.2-.3.2zm-14.8 0c-.1 0-.2-.1-.3-.2C3.9 14 3 12.3 3 10.5s.9-3.6 2.3-4.6c.3-.2.7-.1.9.2.2.3.1.7-.2.9-1 .8-1.7 2.1-1.7 3.5s.7 2.7 1.7 3.5c.3.2.4.6.2.9-.1.3-.3.4-.6.4z"/>
        </svg>
        <span>Tambah Laboran</span>
    </a>

    <a href="{{ route('daftar-lab.index') }}"
       class="menu-item {{ request()->routeIs('daftar-lab.*') ? 'active' : '' }} !no-underline text-white flex items-center gap-2" style="text-decoration: none !important; color: white;">
        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
        <span>Daftar Laboratorium</span>
    </a>

    <a href="{{ route('profile.edit') }}"
   class="menu-item {{ request()->routeIs('profile.*') ? 'active' : '' }} !no-underline text-white flex items-center gap-2" style="text-decoration: none !important; color: white;">
    <svg fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
    </svg>
    <span>Ubah Profile</span>
</a>

    

    <a href="{{ route('aktivitas-administrator') }}"
       class="menu-item {{ request()->is('aktivitas-administrator') ? 'active' : '' }} !no-underline text-white flex items-center gap-2"
       style="text-decoration: none !important; color: white;">
        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M3.5 18.5L9.5 12.5L13.5 16.5L22 6.92L20.59 5.5L13.5 13.5L9.5 9.5L2 17Z"/></svg>
        <span>Aktivitas Administrator</span>
    </a>

    <a href="#"
       class="menu-item !no-underline text-white flex items-center gap-2"
       style="text-decoration: none !important; color: white;">
        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
        <span>Hapus Akun Mahasiswa</span>
    </a>

    <a href="{{ route('pengumuman.index') }}"
       class="menu-item {{ request()->routeIs('pengumuman.*') ? 'active' : '' }} !no-underline text-white flex items-center gap-2"
       style="text-decoration: none !important; color: white;">
        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
        <span>Kelola Pengumuman</span>
    </a>

    <a href="{{ route('admin.alat-lab.index') }}"
   class="menu-item {{ request()->routeIs('admin.alat-lab.*') ? 'active' : '' }} !no-underline text-white flex items-center gap-2"
   style="text-decoration: none !important; color: white;">
    <svg fill="currentColor" viewBox="0 0 24 24">
        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14h-4v-4h4v4zm0-6h-4V7h4v4z"/>
    </svg>
    <span>Kelola Alat Lab</span>
</a>

</div>