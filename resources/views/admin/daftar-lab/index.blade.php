@extends('layouts.app')

@section('content')
<div class="section">
    <div class="section-header">
        <div class="section-title">Daftar Laboratorium dan Departemen</div>
        
        <div class="flex gap-3 items-center">
            <input type="text" name="search" class="search-box" placeholder="Pencarian..." 
                   value="{{ request('search') }}" 
                   hx-get="{{ route('admin.daftar-lab.index') }}" 
                   hx-trigger="keyup changed delay:500ms" 
                   hx-target="#lab-table" hx-include="[name='status']">
                   
            <a href="{{ route('admin.daftar-lab.create') }}" class="btn-primary text-sm">
                + Tambah Laboratorium
            </a>
        </div>
    </div>

    <div class="filter-tabs">
        <button class="filter-tab {{ !request('status') || request('status') == '1' ? 'active' : '' }}"
                onclick="window.location.href='{{ route('admin.daftar-lab.index') }}'">
            Lab Aktif
        </button>
        <button class="filter-tab {{ request('status') == '0' ? 'active' : '' }}"
                onclick="window.location.href='{{ route('admin.daftar-lab.index', ['status' => 0]) }}'">
            Lab Tidak Aktif
        </button>
    </div>

    <div id="lab-table">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Laboratorium</th>
                    <th>Kepala Laboratorium</th>
                    <th>Admin Laboratorium</th>
                    <th>Safety Officer</th>
                    <th>e-mail Laboratorium</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($labs as $i => $lab)
                <tr>
                    <td>{{ $labs->firstItem() + $i }}</td>
                    <td>{{ $lab->Nama_Laboratorium }}</td>
                    <td>{{ $lab->Kepala_Labolatorium }}</td>
                    <td>{{ $lab->Admin_Laboratorium }}</td>
                    <td>{{ $lab->Safety_Officer }}</td>
                    <td>{{ $lab->email_lab }}</td>
                    <td class="flex gap-2">
                        <a href="{{ route('admin.daftar-lab.edit', $lab) }}" class="text-blue-600 hover:underline text-sm">
                            Edit
                        </a>
                        <form action="{{ route('admin.daftar-lab.destroy', $lab) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin hapus laboratorium ini?')"
                                    class="text-red-600 hover:underline text-sm">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-8 text-gray-500">
                        Belum ada data laboratorium
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $labs->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection