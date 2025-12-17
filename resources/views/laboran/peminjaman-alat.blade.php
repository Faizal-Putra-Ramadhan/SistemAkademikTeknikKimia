<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Alat</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="h-full">
    <div class="min-h-full">

        <x-laboran.navbar :labs="$labs" :user="$user" />

        <x-laboran.header>Peminjaman Alat</x-laboran.header>

        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-bold">Daftar Peminjaman Alat</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Peminjam</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Alat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Pinjam</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Kembali</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($peminjamanAlat as $alat)
                                <tr>
                                    <td class="px-6 py-4">{{ $alat->user_nama }}</td>
                                    <td class="px-6 py-4">{{ $alat->alatLab->nama_alat ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($alat->tanggal_pinjam)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4">{{ $alat->tanggal_kembali ? \Carbon\Carbon::parse($alat->tanggal_kembali)->format('d/m/Y') : '-' }}</td>
                                    <td class="px-6 py-4">
                                        @if($alat->status == 'menunggu')
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Menunggu</span>
                                        @elseif($alat->status == 'disetujui')
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">Disetujui</span>
                                        @elseif($alat->status == 'dikembalikan')
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Dikembalikan</span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($alat->status == 'menunggu')
                                        <div class="flex gap-2">
                                            <form action="{{ route('laboran.alat.setujui', $alat->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                                    Setujui
                                                </button>
                                            </form>
                                            <form action="{{ route('laboran.alat.tolak', $alat->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                        @elseif($alat->status == 'disetujui')
                                        <form action="{{ route('laboran.alat.kembalikan', $alat->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                                Tandai Dikembalikan
                                            </button>
                                        </form>
                                        @else
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data peminjaman alat</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>
</body>
</html>