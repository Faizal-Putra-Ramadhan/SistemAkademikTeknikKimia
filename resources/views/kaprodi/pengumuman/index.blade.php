@extends('layouts.app')
@section('title', 'Kelola Pengumuman')
@section('page-title', 'Kelola Pengumuman')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
.card { border: none; border-radius: 12px; }
        .card-header { border-bottom: 1px solid #edf2f7; background-color: #fff; border-radius: 12px 12px 0 0 !important; }
        .card-footer { background-color: #fff; border-top: 1px solid #edf2f7; border-radius: 0 0 12px 12px !important; }
        .btn-primary { background-color: #4f46e5; border-color: #4f46e5; }
        .btn-primary:hover { background-color: #4338ca; border-color: #4338ca; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-bullhorn text-indigo-600 mr-2"></i> Kelola Pengumuman
                </h1>
                <a href="{{ route('kaprodi.pengumuman.create') }}" class="btn btn-primary shadow-sm rounded-lg px-4">
                    <i class="fas fa-plus mr-1"></i> Buat Pengumuman
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-6" role="alert">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-6" role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            @endif

            @if($pengumuman->count() > 0)
                <div class="row">
                    @foreach($pengumuman as $item)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 shadow-sm ring-1 ring-black ring-opacity-5">
                                <div class="card-header d-flex justify-content-between align-items-center py-3">
                                    <span class="badge badge-pill px-3 py-2 badge-{{ $item->status == 'publish' ? 'success' : 'secondary' }}">
                                        {{ $item->status == 'publish' ? 'Published' : 'Draft' }}
                                    </span>
                                    
                                    @if($item->author == $user->Nama)
                                        <div class="btn-group">
                                            <a href="{{ route('kaprodi.pengumuman.edit', $item->id) }}" class="btn btn-sm btn-outline-warning border-0">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('kaprodi.pengumuman.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pengumuman ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h5 class="font-bold text-gray-900 mb-2">{{ $item->judul }}</h5>
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        {{ Str::limit(strip_tags($item->isi), 150) }}
                                    </p>
                                    <div class="mt-4 pt-3 border-top">
                                        <div class="d-flex align-items-center text-muted small">
                                            <div class="mr-3"><i class="fas fa-user-circle mr-1"></i> {{ $item->author }}</div>
                                            <div><i class="fas fa-calendar-alt mr-1"></i> {{ $item->created_at->format('d M Y') }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer py-3">
                                    <button class="btn btn-sm btn-block btn-light text-indigo-600 font-weight-bold" data-toggle="modal" data-target="#detailModal{{ $item->id }}">
                                        <i class="fas fa-eye mr-1"></i> Lihat Detail Selengkapnya
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content border-0 shadow-lg rounded-xl">
                                    <div class="modal-header border-0 pb-0">
                                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                    </div>
                                    <div class="modal-body px-4 pb-4">
                                        <div class="mb-3">
                                            <span class="badge badge-{{ $item->status == 'publish' ? 'success' : 'secondary' }} px-3 py-2">
                                                {{ $item->status == 'publish' ? 'Published' : 'Draft' }}
                                            </span>
                                        </div>
                                        <h3 class="font-bold text-gray-900 mb-2">{{ $item->judul }}</h3>
                                        <div class="text-muted small mb-4">
                                            <i class="fas fa-user mr-1"></i> {{ $item->author }} | 
                                            <i class="fas fa-calendar mr-1"></i> {{ $item->created_at->format('d M Y H:i') }}
                                        </div>
                                        <div class="text-gray-700 leading-loose">
                                            {!! nl2br(e($item->isi)) !!}
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="button" class="btn btn-light rounded-lg px-4" data-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white shadow-sm ring-1 ring-black ring-opacity-5 rounded-xl border border-gray-200">
                    <div class="flex flex-col items-center justify-center py-24 text-center p-md-4">
                        <div class="bg-gray-100 p-6 rounded-2xl mb-4 text-gray-400">
                            <i class="fas fa-bullhorn fa-4x"></i>
                        </div>
                        <h5 class="text-gray-500 font-bold text-lg">Belum ada pengumuman</h5>
                        <p class="text-gray-400 mb-4 px-4">Anda belum membuat pengumuman apapun untuk ditampilkan kepada mahasiswa/dosen.</p>
                        <a href="{{ route('kaprodi.pengumuman.create') }}" class="btn btn-primary shadow-sm px-4 py-2 rounded-lg">
                            <i class="fas fa-plus mr-1 "></i> Buat Pengumuman Sekarang
                        </a>
                    </div>
                </div>
            @endif
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
@endpush
