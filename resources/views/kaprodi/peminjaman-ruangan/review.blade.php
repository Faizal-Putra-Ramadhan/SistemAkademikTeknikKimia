@extends('layouts.app')
@section('title', 'Review Peminjaman Ruangan')
@section('page-title', 'Review Peminjaman Ruangan')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body { background-color: #f4f7f6; }
        .card-detail { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); overflow: hidden; }
        .header-gradient { background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); color: white; padding: 2rem; }
        .info-box { border-radius: 12px; padding: 1.25rem; height: 100%; border: 1px solid rgba(0,0,0,0.05); }
        .decision-card { cursor: pointer; border: 2px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; transition: all 0.3s; }
        input[type="radio"]:checked + .decision-card.setuju { border-color: #10b981; background-color: #f0fdf4; }
        input[type="radio"]:checked + .decision-card.tolak { border-color: #ef4444; background-color: #fef2f2; }
        .btn-submit { border-radius: 10px; padding: 12px 30px; font-weight: 700; letter-spacing: 0.5px; transition: all 0.3s; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4); }
</style>
@endpush

@section('content')
<div class="mb-4">
            <a href="{{ route('kaprodi.peminjaman-ruangan.index') }}" class="btn btn-link text-decoration-none p-0 mb-3 text-muted">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
            </a>
            <h2 class="font-weight-bold text-dark">Review Detail Peminjaman</h2>
        </div>

        @if(session('error'))
            <div class="alert alert-danger shadow-sm border-0 mb-4">
                <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
            </div>
        @endif

        <div class="card card-detail bg-white">
            <div class="header-gradient">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-1 font-weight-bold text-white">{{ $peminjaman->daftarLab->Nama_Laboratorium }}</h3>
                        <p class="mb-0 opacity-75"><i class="fas fa-id-badge mr-2"></i> ID Permohonan: #{{ $peminjaman->id }}</p>
                    </div>
                    <div class="text-right">
                        <span class="badge badge-light px-3 py-2 text-primary font-weight-bold">
                            {{ strtoupper($peminjaman->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="card-body p-4 p-md-5">
                <div class="mb-5">
                    <h5 class="font-weight-bold mb-3"><i class="fas fa-info-circle text-primary mr-2"></i> Informasi Peminjaman</h5>
                    <div class="row no-gutters bg-light rounded-lg p-3">
                        <div class="col-md-6 border-right">
                            <small class="text-muted d-block">Nama Pemohon</small>
                            <span class="font-weight-bold h6 text-dark">{{ $peminjaman->user_nama }}</span>
                        </div>
                        <div class="col-md-6 pl-md-4">
                            <small class="text-muted d-block">Tujuan Penggunaan</small>
                            <span class="font-weight-bold h6 text-dark">Penelitian / Praktikum</span>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="info-box bg-white border">
                            <p class="text-muted small font-weight-bold mb-2 uppercase text-primary">📅 Periode Tanggal</p>
                            <div class="d-flex align-items-center">
                                <div class="text-center bg-primary text-white rounded p-2 mr-3" style="min-width: 60px;">
                                    <span class="d-block h4 mb-0 font-weight-bold">{{ \Carbon\Carbon::parse($peminjaman->tanggal)->format('d') }}</span>
                                    <small>{{ \Carbon\Carbon::parse($peminjaman->tanggal)->format('M') }}</small>
                                </div>
                                <div>
                                    <span class="font-weight-bold text-dark">{{ \Carbon\Carbon::parse($peminjaman->tanggal)->format('d F Y') }}</span>
                                    <div class="small text-muted">s/d {{ \Carbon\Carbon::parse($peminjaman->tanggal_selesai)->format('d F Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box bg-white border">
                            <p class="text-muted small font-weight-bold mb-2 uppercase text-success">🕐 Slot Waktu</p>
                            <div class="h5 font-weight-bold text-dark mb-1">
                                {{ \Carbon\Carbon::parse($peminjaman->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($peminjaman->jam_selesai)->format('H:i') }}
                            </div>
                            <small class="text-muted">Total Durasi: {{ \Carbon\Carbon::parse($peminjaman->jam_mulai)->diffInHours(\Carbon\Carbon::parse($peminjaman->jam_selesai)) }} Jam/Hari</small>
                        </div>
                    </div>
                </div>

                <div class="mb-5 border-top pt-4">
                    <h6 class="font-weight-bold text-dark mb-2">Deskripsi Keperluan:</h6>
                    <blockquote class="blockquote bg-light p-4 rounded" style="font-size: 0.95rem; border-left: 5px solid #3b82f6;">
                        "{{ $peminjaman->keperluan }}"
                    </blockquote>
                </div>

                @if($peminjaman->persetujuan_laboran)
                <div class="alert alert-success border-0 rounded-lg p-4 mb-5">
                    <div class="d-flex align-items-center">
                        <div class="bg-white rounded-circle text-success d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <h6 class="font-weight-bold mb-1">Disetujui oleh Laboran</h6>
                            <p class="small mb-0 opacity-75">Oleh: {{ $peminjaman->laboran->Nama ?? 'Laboran Terkait' }} pada {{ $peminjaman->tanggal_persetujuan_laboran->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    @if($peminjaman->catatan_laboran)
                        <div class="mt-3 small p-3 bg-white rounded text-dark italic border">
                            <strong>Catatan Laboran:</strong> {{ $peminjaman->catatan_laboran }}
                        </div>
                    @endif
                </div>
                @endif

                
                <div class="border-top pt-5">
                    <h4 class="font-weight-bold mb-4 text-center">📋 Status Permohonan</h4>
                    
                    <div class="alert alert-info border-0 rounded-lg p-4 mb-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-white rounded-circle text-info d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div>
                                <h6 class="font-weight-bold mb-1">Status Saat Ini</h6>
                                <p class="mb-0">
                                    @if($peminjaman->status === 'menunggu')
                                        <span class="badge badge-warning">Menunggu Persetujuan Laboran</span>
                                    @elseif($peminjaman->status === 'disetujui_laboran' || $peminjaman->status === 'menunggu_kepala_lab')
                                        <span class="badge badge-primary">Menunggu Persetujuan Kepala Lab</span>
                                    @elseif($peminjaman->status === 'disetujui')
                                        <span class="badge badge-success">Disetujui</span>
                                    @elseif($peminjaman->status === 'ditolak')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @else
                                        <span class="badge badge-secondary">{{ ucfirst($peminjaman->status) }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($peminjaman->persetujuan_kepala_lab !== null)
                    <div class="alert {{ $peminjaman->persetujuan_kepala_lab ? 'alert-success' : 'alert-danger' }} border-0 rounded-lg p-4 mb-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-white rounded-circle {{ $peminjaman->persetujuan_kepala_lab ? 'text-success' : 'text-danger' }} d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                <i class="fas {{ $peminjaman->persetujuan_kepala_lab ? 'fa-check' : 'fa-times' }}"></i>
                            </div>
                            <div>
                                <h6 class="font-weight-bold mb-1">{{ $peminjaman->persetujuan_kepala_lab ? 'Disetujui' : 'Ditolak' }} oleh Kepala Lab</h6>
                                @if($peminjaman->tanggal_persetujuan_kepala_lab)
                                    <p class="small mb-0 opacity-75">Pada {{ $peminjaman->tanggal_persetujuan_kepala_lab->format('d M Y H:i') }}</p>
                                @endif
                            </div>
                        </div>
                        @if($peminjaman->catatan_kepala_lab)
                            <div class="mt-3 small p-3 bg-white rounded text-dark italic border">
                                <strong>Catatan Kepala Lab:</strong> {{ $peminjaman->catatan_kepala_lab }}
                            </div>
                        @endif
                    </div>
                    @endif

                    <div class="alert alert-light border p-3 mb-4">
                        <small class="text-muted">
                            <i class="fas fa-eye mr-2"></i>
                            <strong>Catatan:</strong> Halaman ini hanya untuk monitoring. Persetujuan akhir dilakukan oleh Kepala Lab.
                        </small>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('kaprodi.peminjaman-ruangan.index') }}" class="btn btn-outline-primary btn-submit px-5">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
@endpush
