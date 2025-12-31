<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola User - Teknik UAD</title>
    @vite('resources/css/style.css')
    @vite('resources/js/index.js')
    <style>
        .table-container {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        .filter-section {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .filter-section .form-input {
            flex: 1;
            min-width: 200px;
        }

        .filter-section select.form-input {
            flex: 0 1 200px;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
        }

        .user-table thead {
            background: #f8f9fa;
        }

        .user-table th {
            padding: 12px 16px;
            text-align: left;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
        }

        .user-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #dee2e6;
        }

        .user-table tbody tr:hover {
            background: #f8f9fa;
        }

        .role-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .role-admin { background: #e3f2fd; color: #1976d2; }
        .role-dosen { background: #f3e5f5; color: #7b1fa2; }
        .role-mahasiswa { background: #e8f5e9; color: #388e3c; }
        .role-safety { background: #fff3e0; color: #f57c00; }
        .role-kepala { background: #fce4ec; color: #c2185b; }
        .role-laboran { background: #e0f2f1; color: #00796b; }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-icon {
            padding: 8px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            width: 32px;
            height: 32px;
        }

        .btn-icon svg {
            width: 16px;
            height: 16px;
        }

        .btn-edit {
            background: #2196f3;
            color: white;
        }

        .btn-edit:hover {
            background: #1976d2;
        }

        .btn-reset {
            background: #ff9800;
            color: white;
        }

        .btn-reset:hover {
            background: #f57c00;
        }

        .btn-delete {
            background: #f44336;
            color: white;
        }

        .btn-delete:hover {
            background: #d32f2f;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 24px;
        }

        .pagination a, .pagination span {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #dee2e6;
            text-decoration: none;
            color: #495057;
        }

        .pagination a:hover {
            background: #f8f9fa;
        }

        .pagination .active {
            background: #2196f3;
            color: white;
            border-color: #2196f3;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 24px;
            border-radius: 12px;
            max-width: 400px;
            width: 90%;
        }

        .modal-header {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .modal-actions {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }
    </style>
</head>
<body>
    
    <x-header></x-header>
    <div class="container">
        <x-sidebar></x-sidebar>
        <div class="main-content" id="mainContent">
            
            <div class="page-header">
                <h1 class="page-title">Kelola User</h1>
                <p class="page-subtitle">Lihat, edit, hapus, dan kelola data user dalam sistem</p>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
            <div class="alert alert-success">
                <svg class="alert-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-error">
                <svg class="alert-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
            @endif

            <!-- Table Container -->
            <div class="table-container">
                <!-- Filter Section -->
                <form action="{{ route('admin.kelola-user.index') }}" method="GET" class="filter-section">
                    <input 
                        type="text" 
                        name="search" 
                        class="form-input" 
                        placeholder="🔍 Cari nama, email, atau User ID..."
                        value="{{ request('search') }}"
                    >
                    <select name="role" class="form-input">
                        <option value="">Semua Role</option>
                        <option value="Admin" {{ request('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                        <option value="Dosen" {{ request('role') == 'Dosen' ? 'selected' : '' }}>Dosen</option>
                        <option value="Mahasiswa" {{ request('role') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        <option value="Safety Officer" {{ request('role') == 'Safety Officer' ? 'selected' : '' }}>Safety Officer</option>
                        <option value="Kepala Laboratorium" {{ request('role') == 'Kepala Laboratorium' ? 'selected' : '' }}>Kepala Laboratorium</option>
                        <option value="Laboran" {{ request('role') == 'Laboran' ? 'selected' : '' }}>Laboran</option>
                    </select>
                    <button type="submit" class="btn btn-primary">
                        <svg class="btn-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                        </svg>
                        Filter
                    </button>
                    @if(request('search') || request('role'))
                    <a href="{{ route('admin.kelola-user.index') }}" class="btn btn-secondary">Reset</a>
                    @endif
                </form>

                <!-- User Table -->
                <table class="user-table">
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
                                <span class="role-badge role-{{ strtolower(str_replace(' ', '-', $user->Role_User)) }}">
                                    {{ $user->Role_User }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.kelola-user.edit', $user->id) }}" class="btn-icon btn-edit" title="Edit">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                    </a>

                                    <!-- Reset Password Button -->
                                    <a href="{{ route('admin.kelola-user.reset-password', $user->id) }}" class="btn-icon btn-reset" title="Reset Password">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </a>

                                    <!-- Delete Button -->
                                    <button 
                                        type="button" 
                                        class="btn-icon btn-delete" 
                                        onclick="confirmDelete({{ $user->id }}, '{{ $user->Nama }}')"
                                        title="Hapus"
                                    >
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px;">
                                <p style="color: #6c757d;">Tidak ada user ditemukan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination">
                    {{ $users->links() }}
                </div>
            </div>

        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-content">
            <div class="modal-header">Konfirmasi Hapus User</div>
            <p>Apakah Anda yakin ingin menghapus user <strong id="userName"></strong>?</p>
            <p style="color: #dc3545; font-size: 14px;">⚠️ Tindakan ini tidak dapat dibatalkan!</p>
            
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus User</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function confirmDelete(userId, userName) {
            document.getElementById('userName').textContent = userName;
            document.getElementById('deleteForm').action = `/admin/kelola-user/${userId}`;
            document.getElementById('deleteModal').classList.add('active');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('active');
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
    
</body>
</html>