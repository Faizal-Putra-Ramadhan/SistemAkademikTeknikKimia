@extends('layouts.app')

@section('title', 'Kelola Akun Terhubung')
@section('page-title', 'Kelola Akun Terhubung')

@push('styles')
<style>
    .linked-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 24px; }
    @media (max-width: 900px) { .linked-grid { grid-template-columns: 1fr; } }

    .account-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        margin-bottom: 12px;
        transition: all 0.2s ease;
        background: #fff;
    }
    .account-card:hover { 
        border-color: #c7d2fe; 
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.08);
    }
    .account-card.primary { 
        background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
        border-color: #a5b4fc;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.12);
    }
    .account-left { display: flex; align-items: center; gap: 16px; }
    .account-avatar {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-weight: 700; font-size: 18px; flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .avatar-dosen { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
    .avatar-mahasiswa { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .avatar-safety { background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%); }
    .avatar-kepala { background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%); }
    .avatar-laboran { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); }
    .avatar-admin { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); }
    .avatar-kaprodi { background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); }
    .avatar-peneliti { background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); }
    .avatar-default { background: linear-gradient(135deg, #64748b 0%, #475569 100%); }

    .account-name { font-size: 15px; font-weight: 600; color: #1f2937; }
    .account-email { font-size: 13px; color: #6b7280; margin-top: 2px; }
    .account-meta { font-size: 11px; color: #9ca3af; margin-top: 4px; display: flex; gap: 8px; flex-wrap: wrap; }

    .badge-primary-custom {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        background: #4f46e5;
        color: #fff;
        margin-left: 8px;
    }

    .btn-unlink {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        background: #fee2e2;
        color: #dc2626;
        border: 1px solid #fecaca;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-unlink:hover {
        background: #fecaca;
        border-color: #f87171;
    }

    .link-form-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .link-form-header {
        padding: 18px 20px;
        border-bottom: 1px solid #e5e7eb;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
    }
    .link-form-body { padding: 20px; }
    .form-group-modern { margin-bottom: 18px; }
    .form-label-modern {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }
    .form-control-modern {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control-modern:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }
    .btn-link-submit {
        width: 100%;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: #fff;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(99, 102, 241, 0.3);
    }
    .btn-link-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
    }

    .info-card {
        margin-top: 20px;
        padding: 18px 20px;
        border-radius: 12px;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border: 1px solid #bfdbfe;
    }
    .info-card strong { color: #1e40af; font-size: 14px; }
    .note-list { margin: 10px 0 0 20px; font-size: 13px; line-height: 1.8; color: #1e3a8a; }
    .note-list li { margin-bottom: 4px; }
</style>
@endpush

@section('content')
    <div class="linked-grid">
        <!-- Left: Linked accounts list -->
        <div class="link-form-card">
            <div class="link-form-header">
                <span>Akun yang Terhubung</span>
            </div>
            <div class="link-form-body">
                @foreach($linkedAccounts as $account)
                    @php
                        $avatarClass = match($account->Role_User) {
                            'Dosen' => 'avatar-dosen',
                            'Mahasiswa' => 'avatar-mahasiswa',
                            'Safety Officer' => 'avatar-safety',
                            'Kepala Laboratorium' => 'avatar-kepala',
                            'Laboran' => 'avatar-laboran',
                            'Admin' => 'avatar-admin',
                            'Kaprodi' => 'avatar-kaprodi',
                            'Peneliti Eksternal' => 'avatar-peneliti',
                            default => 'avatar-default'
                        };
                    @endphp
                    <div class="account-card {{ $account->is_primary ? 'primary' : '' }}">
                        <div class="account-left">
                            <div class="account-avatar {{ $avatarClass }}">{{ strtoupper(substr($account->Nama, 0, 1)) }}</div>
                            <div>
                                <div class="account-name">
                                    {{ $account->Nama }}
                                    @if($account->is_primary)
                                        <span class="badge-primary-custom">Utama</span>
                                    @endif
                                </div>
                                <div class="account-email">{{ $account->Email }}</div>
                                <div class="account-meta">
                                    <span>Role: {{ $account->Role_User }}</span>
                                    <span>•</span>
                                    <span>ID: {{ $account->UserID }}</span>
                                </div>
                            </div>
                        </div>
                        @if(!$account->is_primary)
                            <form method="POST" action="{{ route('role.unlink', $account->id) }}" onsubmit="return confirm('Yakin ingin memutuskan koneksi akun ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-unlink">Putuskan</button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Right: Link new account form -->
        <div>
            <div class="link-form-card">
                <div class="link-form-header">
                    <span>Hubungkan Akun Baru</span>
                </div>
                <div class="link-form-body">
                    <form method="POST" action="{{ route('role.link') }}">
                        @csrf
                        <div class="form-group-modern">
                            <label for="target_email" class="form-label-modern">Email Akun yang Ingin Dihubungkan</label>
                            <input type="email" name="target_email" id="target_email" class="form-control-modern" placeholder="contoh@email.com" required>
                            @error('target_email')
                                <span style="color: #dc2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group-modern">
                            <label for="current_password" class="form-label-modern">Password Saat Ini</label>
                            <input type="password" name="current_password" id="current_password" class="form-control-modern" placeholder="Masukkan password Anda" required>
                            @error('current_password')
                                <span style="color: #dc2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn-link-submit">Hubungkan Akun</button>
                    </form>
                </div>
            </div>

            <!-- Info note -->
            <div class="info-card">
                <strong>Catatan Penting</strong>
                <ul class="note-list">
                    <li>Akun yang dihubungkan harus sudah terdaftar di sistem</li>
                    <li>Satu akun hanya bisa terhubung ke satu akun utama</li>
                    <li>Anda dapat beralih antar peran dengan mudah setelah menghubungkan akun</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
