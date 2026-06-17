@extends('layouts.app')

@section('title', 'Kelola User')
@section('page-title', 'Kelola User')

@push('styles')
<style>
    .filter-section {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .filter-section .form-control { flex: 1; min-width: 200px; }
    .filter-section select.form-control { flex: 0 1 200px; }

    .role-badge {
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
    .role-safety-officer { background: #fef3c7; color: #92400e; }
    .role-kepala-laboratorium { background: #fce7f3; color: #be185d; }
    .role-laboran { background: #ccfbf1; color: #0f766e; }
    .role-peneliti-eksternal { background: #e0e7ff; color: #4338ca; }
    .role-kaprodi { background: #fce7f3; color: #9d174d; }

    .action-buttons { display: flex; gap: 6px; }

    .btn-icon {
        padding: 6px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        width: 30px; height: 30px;
        text-decoration: none;
    }
    .btn-icon svg { width: 15px; height: 15px; }
    .btn-icon.edit { background: var(--color-primary); color: #fff; }
    .btn-icon.edit:hover { background: var(--color-primary-hover); }
    .btn-icon.reset { background: #d97706; color: #fff; }
    .btn-icon.reset:hover { background: #b45309; }
    .btn-icon.delete { background: #dc2626; color: #fff; }
    .btn-icon.delete:hover { background: #b91c1c; }

    .confirm-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.45);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }
    .confirm-modal.active { display: flex; }
    .confirm-modal-box {
        background: #fff;
        padding: 24px;
        border-radius: 10px;
        max-width: 400px;
        width: 90%;
        border: 1px solid #e5e7eb;
    }
    .confirm-modal-title {
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 12px;
    }
    .confirm-modal-box p { font-size: 13.5px; color: #374151; margin-bottom: 8px; }
    .confirm-modal-actions { display: flex; gap: 10px; margin-top: 20px; }
</style>
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Kelola User</h3>
        </div>
        <div class="card-body">
            <!-- Filter Section -->
            <form action="{{ route('admin.kelola-user.index') }}" method="GET" class="filter-section">
                <input type="text" name="search" class="form-control" placeholder="Cari nama, email, atau User ID..." value="{{ request('search') }}">
                <select name="role" class="form-control">
                    <option value="">Semua Role</option>
                    <option value="Admin" {{ request('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="Dosen" {{ request('role') == 'Dosen' ? 'selected' : '' }}>Dosen</option>
                    <option value="Mahasiswa" {{ request('role') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="Peneliti Eksternal" {{ request('role') == 'Peneliti Eksternal' ? 'selected' : '' }}>Peneliti Eksternal</option>
                    <option value="Safety Officer" {{ request('role') == 'Safety Officer' ? 'selected' : '' }}>Safety Officer</option>
                    <option value="Kepala Laboratorium" {{ request('role') == 'Kepala Laboratorium' ? 'selected' : '' }}>Kepala Laboratorium</option>
                    <option value="Laboran" {{ request('role') == 'Laboran' ? 'selected' : '' }}>Laboran</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/></svg>
                    Filter
                </button>
                @if(request('search') || request('role'))
                <a href="{{ route('admin.kelola-user.index') }}" class="btn btn-outline btn-sm">Reset</a>
                @endif
            </form>

            <!-- User Table -->
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Role</th>
                            <th>Terdaftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td><strong>{{ $user->UserID }}</strong></td>
                            <td>{{ $user->Nama }}</td>
                            <td>{{ $user->Email }}</td>
                            <td>{{ $user->Phone }}</td>
                            <td>
                                @php
                                    $userRoles = $user->roleNames;
                                    if (empty($userRoles) && $user->Role_User) {
                                        $userRoles = [$user->Role_User];
                                    }
                                    $primaryRole = $user->primaryRole()?->name ?? $user->Role_User;
                                @endphp
                                <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                    @foreach($userRoles as $roleName)
                                        @php
                                            $roleClass = 'role-' . strtolower(str_replace(' ', '-', $roleName));
                                            $isPrimary = $roleName === $primaryRole;
                                        @endphp
                                        <span class="role-badge {{ $roleClass }}" style="{{ $isPrimary ? 'border: 2px solid #1d4ed8; font-weight: 600;' : '' }}" title="{{ $isPrimary ? 'Primary Role' : '' }}">
                                            {{ $roleName }}
                                            @if($isPrimary && count($userRoles) > 1)
                                                <span style="margin-left: 4px; font-size: 10px;">★</span>
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.kelola-user.edit', $user->id) }}" class="btn-icon edit" title="Edit">
                                        <svg fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.kelola-user.reset-password', $user->id) }}" class="btn-icon reset" title="Reset Password">
                                        <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                                    </a>
                                    <button type="button" class="btn-icon delete" onclick="confirmDelete({{ $user->id }}, '{{ addslashes($user->Nama) }}')" title="Hapus">
                                        <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #6b7280;">
                                Tidak ada user ditemukan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div style="margin-top: 20px;">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="confirm-modal" id="deleteModal">
        <div class="confirm-modal-box">
            <div class="confirm-modal-title">Konfirmasi Hapus User</div>
            <p>Apakah Anda yakin ingin menghapus user <strong id="userName"></strong>?</p>
            <p style="color: #dc2626; font-size: 12px;">Tindakan ini tidak dapat dibatalkan!</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="confirm-modal-actions">
                    <button type="button" class="btn btn-outline btn-sm" onclick="closeDeleteModal()">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm">Hapus User</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function confirmDelete(userId, userName) {
        document.getElementById('userName').textContent = userName;
        document.getElementById('deleteForm').action = `/admin/kelola-user/${userId}`;
        document.getElementById('deleteModal').classList.add('active');
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('active');
    }
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });
</script>
@endpush