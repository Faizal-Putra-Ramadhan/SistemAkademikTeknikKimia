@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px; }
    .section-gap { margin-bottom: 24px; }
    .role-cell { white-space: nowrap; }
    .role-tag {
        display: inline-flex;
        align-items: center;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 11.5px;
        font-weight: 600;
    }
    .role-admin { background: #dbeafe; color: #1d4ed8; }
    .role-dosen { background: #ede9fe; color: #7c3aed; }
    .role-mahasiswa { background: #d1fae5; color: #065f46; }
    .role-laboran { background: #ccfbf1; color: #0f766e; }
    .role-safety-officer { background: #fef3c7; color: #92400e; }
    .role-kepala-laboratorium { background: #fce7f3; color: #be185d; }
    .role-kaprodi { background: #fce7f3; color: #9d174d; }
    .role-peneliti-eksternal { background: #e0e7ff; color: #4338ca; }
    .role-tendik { background: #fef3c7; color: #92400e; }
    .status-aktif { background: #d1fae5; color: #065f46; }
</style>
@endpush

@section('content')
    {{-- Welcome --}}
    <div class="welcome-card">
        <h2>Selamat Datang, {{ Auth::user()->Nama ?? 'Admin' }}</h2>
        <p>Panel administrasi Lab Teknik Kimia UAD</p>
    </div>

    {{-- Stat Cards --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
            </div>
            <div class="stat-info">
                <p>Admin</p>
                <h3>{{ $daftar_users->filter(fn($u) => $u->Role_User === 'Admin' || $u->roles->contains('name', 'Admin'))->count() }}</h3>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div class="stat-info">
                <p>Laboran</p>
                <h3>{{ $daftar_laborans->count() }}</h3>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <div class="stat-info">
                <p>Dosen</p>
                <h3>{{ $daftar_users->filter(fn($u) => $u->Role_User === 'Dosen' || $u->roles->contains('name', 'Dosen'))->count() }}</h3>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon yellow">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <div class="stat-info">
                <p>Mahasiswa</p>
                <h3>{{ $daftar_users->filter(fn($u) => $u->Role_User === 'Mahasiswa' || $u->roles->contains('name', 'Mahasiswa'))->count() }}</h3>
            </div>
        </div>
    </div>

    {{-- Daftar Laboran --}}
    <div class="card section-gap">
        <div class="card-header">
            <h3>Daftar Laboran</h3>
        </div>
        <div class="card-body">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Laboratorium</th>
                            <th>Nama Laboran</th>
                            <th>User ID</th>
                            <th>Telepon</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($daftar_laborans as $i => $daftar_laboran)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $daftar_laboran['Laboratorium'] }}</td>
                            <td>{{ $daftar_laboran['Nama_Laboran'] }}</td>
                            <td>{{ $daftar_laboran['UserID'] }}</td>
                            <td>{{ $daftar_laboran['Phone'] }}</td>
                            <td>{{ $daftar_laboran['Email'] }}</td>
                            <td class="role-cell">
                                <span class="role-tag role-laboran">{{ $daftar_laboran['Role_User'] }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" style="text-align:center; padding:24px; color:#6b7280;">Belum ada data laboran</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Daftar User --}}
    <div class="card section-gap">
        <div class="card-header">
            <h3>Daftar User</h3>
        </div>
        <div class="card-body">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Telepon</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($daftar_users as $i => $daftar_user)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $daftar_user->Nama }}</td>
                            <td>{{ $daftar_user->Phone }}</td>
                            <td>{{ $daftar_user->Email }}</td>
                            <td class="role-cell">
                                @php
                                    $userRoles = $daftar_user->roleNames;
                                    if (empty($userRoles) && $daftar_user->Role_User) {
                                        $userRoles = [$daftar_user->Role_User];
                                    }
                                @endphp
                                <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                    @foreach($userRoles as $roleName)
                                        @php
                                            $roleClass = 'role-' . strtolower(str_replace(' ', '-', $roleName));
                                        @endphp
                                        <span class="role-tag {{ $roleClass }}">{{ $roleName }}</span>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="text-align:center; padding:24px; color:#6b7280;">Belum ada data user</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Daftar Laboratorium --}}
    <div class="card">
        <div class="card-header">
            <h3>Daftar Laboratorium</h3>
        </div>
        <div class="card-body">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Laboratorium</th>
                            <th>Kepala Lab</th>
                            <th>Admin Lab</th>
                            <th>Safety Officer</th>
                            <th>Email</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($daftar_labs as $i => $daftar_lab)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $daftar_lab['Nama_Laboratorium'] }}</td>
                            <td>{{ $daftar_lab['Kepala_Labolatorium'] }}</td>
                            <td>{{ $daftar_lab['Admin_Laboratorium'] }}</td>
                            <td>{{ $daftar_lab['Safety_Officer'] }}</td>
                            <td>{{ $daftar_lab['email_lab'] }}</td>
                            <td><span class="badge badge-success">Aktif</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="7" style="text-align:center; padding:24px; color:#6b7280;">Belum ada data laboratorium</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection