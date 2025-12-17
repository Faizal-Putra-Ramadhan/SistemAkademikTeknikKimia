<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Teknik UAD</title>
    @vite('resources/css/style.css')
    @vite('resources/js/index.js')
</head>
<body>
    
    <x-header></x-header>
    <div class="container">
        <x-sidebar></x-sidebar>
        <div class="main-content" id="mainContent">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon admin">⚙️</div>
                    <div class="stat-info">
                        <h3>Admin</h3>
                        <p>{{ $daftar_users->where('Role_User', 'Admin')->count() }}</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon laboran">👥</div>
                    <div class="stat-info">
                        <h3>Laboran</h3>
                        <p>{{ $daftar_laborans->count() }}</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon dosen">👥</div>
                    <div class="stat-info">
                        <h3>Dosen</h3>
                        <p>{{ $daftar_users->where('Role_User', 'Dosen')->count() }}</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon members">👥</div>
                    <div class="stat-info">
                        <h3>Members</h3>
                        <p>{{ $daftar_users->where('Role_User', 'Mahasiswa')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <x-section-daftar-laboran>
                <?php $i = 1; ?>
                @foreach ($daftar_laborans as $daftar_laboran)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $daftar_laboran['Laboratorium'] }}</td>
                    <td>{{ $daftar_laboran['Nama_Laboran'] }}</td>
                    <td>{{ $daftar_laboran['UserID'] }}</td>
                    <td>{{ $daftar_laboran['Phone'] }}</td>
                    <td>{{ $daftar_laboran['Email'] }}</td>
                    <td>
                        <div class="role-badge-wrapper">
                            <span class="role-badge" onclick="toggleRoleDropdown(event, 'roleDropdown-laboran-{{ $i }}')">
                                {{ $daftar_laboran['Role_User'] }} ▼
                            </span>
                            <div class="role-dropdown" id="roleDropdown-laboran-{{ $i }}">
                                <button class="role-option {{ $daftar_laboran['Role_User'] == 'Admin' ? 'active' : '' }}" 
                                        onclick="updateRole('{{ $daftar_laboran['id'] ?? $i }}', 'Admin', 'laboran')">
                                    <span class="role-icon"></span> Admin
                                </button>
                                <button class="role-option {{ $daftar_laboran['Role_User'] == 'Tendik' ? 'active' : '' }}" 
                                        onclick="updateRole('{{ $daftar_laboran['id'] ?? $i }}', 'Tendik', 'laboran')">
                                    <span class="role-icon"></span> Tendik
                                </button>
                                <button class="role-option {{ $daftar_laboran['Role_User'] == 'Dosen' ? 'active' : '' }}" 
                                        onclick="updateRole('{{ $daftar_laboran['id'] ?? $i }}', 'Dosen', 'laboran')">
                                    <span class="role-icon"></span> Dosen
                                </button>
                                <button class="role-option {{ $daftar_laboran['Role_User'] == 'Mahasiswa' ? 'active' : '' }}" 
                                        onclick="updateRole('{{ $daftar_laboran['id'] ?? $i }}', 'Mahasiswa', 'laboran')">
                                    <span class="role-icon"></span> Mahasiswa
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php $i++; ?>
                @endforeach
            </x-section-daftar-laboran>
            
            <x-section-daftar-user>
                <?php $i = 1; ?>
                @foreach ($daftar_users as $daftar_user)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $daftar_user['Nama'] }}</td>
                    <td>{{ $daftar_user['Phone'] }}</td>
                    <td>{{ $daftar_user['Email'] }}</td>
                    <td>
                        <div class="role-badge-wrapper">
                            <span class="role-badge" onclick="toggleRoleDropdown(event, 'roleDropdown-user-{{ $i }}')">
                                {{ $daftar_user['Role_User'] }} ▼
                            </span>
                            <div class="role-dropdown" id="roleDropdown-user-{{ $i }}">
                                <button class="role-option {{ $daftar_user['Role_User'] == 'Admin' ? 'active' : '' }}" 
                                        onclick="updateRole('{{ $daftar_user['id'] ?? $i }}', 'Admin', 'user')">
                                    <span class="role-icon"></span> Admin
                                </button>
                                <button class="role-option {{ $daftar_user['Role_User'] == 'Tendik' ? 'active' : '' }}" 
                                        onclick="updateRole('{{ $daftar_user['id'] ?? $i }}', 'Tendik', 'user')">
                                    <span class="role-icon"></span> Tendik
                                </button>
                                <button class="role-option {{ $daftar_user['Role_User'] == 'Dosen' ? 'active' : '' }}" 
                                        onclick="updateRole('{{ $daftar_user['id'] ?? $i }}', 'Dosen', 'user')">
                                    <span class="role-icon"></span> Dosen
                                </button>
                                <button class="role-option {{ $daftar_user['Role_User'] == 'Mahasiswa' ? 'active' : '' }}" 
                                        onclick="updateRole('{{ $daftar_user['id'] ?? $i }}', 'Mahasiswa', 'user')">
                                    <span class="role-icon"></span> Mahasiswa
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php $i++; ?>
                @endforeach
            </x-section-daftar-user>
            
            <x-section-daftar-lab>
                <?php $i = 1; ?>
                @foreach ($daftar_labs as $daftar_lab)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $daftar_lab['Nama_Laboratorium'] }}</td>
                    <td>{{ $daftar_lab['Kepala_Labolatorium'] }}</td>
                    <td>{{ $daftar_lab['Admin_Laboratorium'] }}</td>
                    <td>{{ $daftar_lab['Safety_Officer'] }}</td>
                    <td>{{ $daftar_lab['email_lab'] }}</td>
                    <td><button class="action-btn">Lab Aktif 📝</button></td>
                </tr>
                <?php $i++; ?>
                @endforeach
            </x-section-daftar-lab>
        </div>
    </div>
    
</body>
</html>