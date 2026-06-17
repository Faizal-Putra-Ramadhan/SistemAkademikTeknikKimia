@extends('layouts.app')
@section('title', 'Profil Peneliti')
@section('page-title', 'Profil')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&family=Space+Grotesk:wght@500;600;700&display=swap');

    .profile-page {
        position: relative;
        max-width: 980px;
        margin: 0 auto;
        padding: 1.5rem 1.25rem 2.5rem;
        font-family: 'Space Grotesk', 'Manrope', 'Segoe UI', sans-serif;
    }
    .profile-page::before {
        content: '';
        position: absolute;
        top: -120px;
        right: -80px;
        width: 260px;
        height: 260px;
        background: radial-gradient(circle at 30% 30%, rgba(15, 111, 255, 0.25), transparent 70%);
        z-index: 0;
    }
    .profile-page::after {
        content: '';
        position: absolute;
        bottom: -140px;
        left: -100px;
        width: 320px;
        height: 320px;
        background: radial-gradient(circle at 40% 40%, rgba(28, 176, 140, 0.2), transparent 70%);
        z-index: 0;
    }
    .profile-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }
    .profile-back {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: #1f2937;
        color: #fff;
        padding: 0.55rem 0.95rem;
        border-radius: 999px;
        text-decoration: none;
        font-weight: 600;
        box-shadow: 0 10px 20px rgba(15, 23, 42, 0.18);
    }
    .profile-alert {
        padding: 0.9rem 1rem;
        border-radius: 12px;
        margin-bottom: 1rem;
        font-weight: 600;
        position: relative;
        z-index: 1;
    }
    .profile-alert.success {
        background: #e7f6ef;
        color: #0f5132;
        border: 1px solid #bfe7d3;
    }
    .profile-alert.error {
        background: #fdecea;
        color: #7a271a;
        border: 1px solid #f9c2bb;
    }
    .profile-shell {
        position: relative;
        z-index: 1;
        background: #fff;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid rgba(15, 23, 42, 0.06);
        box-shadow: 0 28px 70px rgba(15, 23, 42, 0.12);
        animation: profileRise 0.5s ease;
    }
    .profile-hero {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding: 2rem;
        background: linear-gradient(120deg, #0f6fff 0%, #1cb08c 100%);
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .profile-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.3), transparent 55%);
        opacity: 0.6;
    }
    .profile-hero > * {
        position: relative;
        z-index: 1;
    }
    .profile-avatar {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.18);
        border: 3px solid rgba(255, 255, 255, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: #fff;
        overflow: hidden;
        flex-shrink: 0;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.2);
    }
    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .profile-title {
        font-size: 1.6rem;
        margin: 0;
        font-weight: 700;
    }
    .profile-subtitle {
        margin: 0.25rem 0 0;
        font-weight: 600;
        opacity: 0.9;
    }
    .profile-id {
        margin: 0.4rem 0 0;
        font-size: 0.9rem;
        opacity: 0.85;
    }
    .profile-body {
        padding: 2rem;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.92), #fff);
    }
    .profile-note {
        border: 1px solid rgba(15, 111, 255, 0.2);
        background: rgba(15, 111, 255, 0.06);
        padding: 1rem 1.25rem;
        border-radius: 16px;
        margin-bottom: 1.5rem;
        color: #475467;
    }
    .profile-note-title {
        font-weight: 700;
        margin: 0 0 0.25rem;
        color: #0f1f2e;
    }
    .profile-form {
        display: grid;
        gap: 1rem;
    }
    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
    }
    .form-field {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .form-field.full {
        grid-column: 1 / -1;
    }
    .profile-label {
        font-weight: 600;
        color: #1f2937;
    }
    .profile-input {
        width: 100%;
        padding: 0.75rem 0.9rem;
        border: 1px solid #d5dce6;
        border-radius: 12px;
        background: #fff;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        font-size: 1rem;
    }
    .profile-input:focus {
        outline: none;
        border-color: #0f6fff;
        box-shadow: 0 0 0 3px rgba(15, 111, 255, 0.15);
    }
    .profile-help {
        font-size: 0.85rem;
        color: #667085;
    }
    .form-error {
        font-size: 0.85rem;
        color: #d92d20;
    }
    .profile-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: 0.5rem;
        flex-wrap: wrap;
    }
    .profile-btn {
        border: none;
        background: linear-gradient(120deg, #0f6fff, #1cb08c);
        color: #fff;
        padding: 0.75rem 1.4rem;
        border-radius: 999px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 12px 24px rgba(15, 111, 255, 0.2);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .profile-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 16px 30px rgba(15, 111, 255, 0.25);
    }
    @keyframes profileRise {
        from {
            transform: translateY(12px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    @media (max-width: 640px) {
        .profile-hero {
            flex-direction: column;
            align-items: flex-start;
        }
        .profile-avatar {
            width: 80px;
            height: 80px;
        }
    }
</style>
@endpush

@section('content')
    <div class="profile-page">
        <div class="profile-top">
            <a href="{{ route('peneliti-eksternal.dashboard') }}" class="profile-back">← Kembali ke Dashboard</a>
        </div>

        @if(session('success'))
            <div class="profile-alert success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="profile-alert error">{{ session('error') }}</div>
        @endif

        <div class="profile-shell">
            <div class="profile-hero">
                <div class="profile-avatar">
                    @if($user->foto)
                        <img src="{{ asset('uploads/profile/' . $user->foto) }}" alt="Foto Profil">
                    @else
                        👨‍🏫
                    @endif
                </div>
                <div>
                    <h2 class="profile-title">{{ $user->Nama }}</h2>
                    <p class="profile-subtitle">{{ $user->Role_User }}</p>
                    <p class="profile-id">User ID: {{ $user->UserID }}</p>
                </div>
            </div>

            <div class="profile-body">
                <div class="profile-note">
                    <p class="profile-note-title">Informasi</p>
                    <p>Update informasi profil Anda. Pastikan data yang dimasukkan valid.</p>
                </div>

                <form action="{{ route('peneliti-eksternal.profil.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
                    @csrf

                    <div class="form-row">
                        <div class="form-field">
                            <label class="profile-label" for="Nama">Nama Lengkap *</label>
                            <input type="text" class="profile-input" id="Nama" name="Nama" required value="{{ old('Nama', $user->Nama) }}">
                            @error('Nama')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-field">
                            <label class="profile-label" for="Email">Email *</label>
                            <input type="email" class="profile-input" id="Email" name="Email" required value="{{ old('Email', $user->Email) }}">
                            @error('Email')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-field">
                            <label class="profile-label" for="Phone">No. Telepon</label>
                            <input type="text" class="profile-input" id="Phone" name="Phone" value="{{ old('Phone', $user->Phone) }}">
                            @error('Phone')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-field full">
                            <label class="profile-label" for="foto">Foto Profil</label>
                            <input type="file" class="profile-input" id="foto" name="foto" accept="image/jpeg,image/png,image/jpg">
                            <div class="profile-help">Format: JPG, JPEG, PNG (Max: 2MB)</div>
                            @error('foto')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="profile-actions">
                        <button type="submit" class="profile-btn">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
