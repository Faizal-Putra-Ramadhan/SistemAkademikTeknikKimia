<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Penelitian</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="h-full">
    <div class="min-h-full">

        <x-laboran.navbar :labs="$labs" :user="$user" />

        <x-laboran.header>Pengajuan Penelitian</x-laboran.header>

        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-bold">Daftar Pengajuan Penelitian</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Mahasiswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul Penelitian</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dosen Pembimbing</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($pengajuanPenelitian as $penelitian)
                                <tr>
                                    <td class="px-6 py-4">{{ $penelitian->user_nama }}</td>
                                    <td class="px-6 py-4">{{ Str::limit($penelitian->judul_penelitian, 50) }}</td>
                                    <td class="px-6 py-4">{{ $penelitian->dosen_pembimbing }}</td>
                                    <td class="px-6 py-4">
                                        {{ \Carbon\Carbon::parse($penelitian->tanggal_mulai)->format('d/m/Y') }} - 
                                        {{ \Carbon\Carbon::parse($penelitian->tanggal_selesai)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($penelitian->status == 'menunggu')
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Menunggu</span>
                                        @elseif($penelitian->status == 'disetujui')
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Disetujui</span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($penelitian->status == 'menunggu')
                                        <div class="flex gap-2">
                                            <button onclick="showDetailPenelitian({{ $penelitian->id }})" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                                Detail
                                            </button>
                                            <form action="{{ route('laboran.penelitian.setujui', $penelitian->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                                    Setujui
                                                </button>
                                            </form>
                                            <form action="{{ route('laboran.penelitian.tolak', $penelitian->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                        @else
                                            <button onclick="showDetailPenelitian({{ $penelitian->id }})" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                                Detail
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data pengajuan penelitian</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
    function showDetailPenelitian(id) {
        // Implementasi detail penelitian dengan modal atau redirect
        window.location.href = '/laboran/penelitian/' + id;
    }
    </script>
</body>
</html>