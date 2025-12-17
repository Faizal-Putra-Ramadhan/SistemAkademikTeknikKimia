<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengumuman</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="h-full">
    <div class="min-h-full">

        <x-laboran.navbar :labs="$labs" :user="$user" />

        <x-laboran.header>Kelola Pengumuman</x-laboran.header>

        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b flex justify-between items-center">
                        <h2 class="text-xl font-bold">Daftar Pengumuman</h2>
                        <a href="{{ route('laboran.pengumuman.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Buat Pengumuman
                        </a>
                    </div>
                    <div class="p-6">
                        @forelse($pengumuman as $item)
                        <div class="border rounded-lg p-4 mb-4">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold">{{ $item->judul }}</h3>
                                    <p class="text-sm text-gray-500">{{ $item->author }} • {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y H:i') }}</p>
                                </div>
                                <div class="flex gap-2">
                                    @if($item->status == 'publish')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Published</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">Draft</span>
                                    @endif
                                </div>
                            </div>
                            <p class="text-gray-700 mb-4">{{ Str::limit($item->isi, 200) }}</p>
                            <div class="flex gap-2">
                                <a href="{{ route('laboran.pengumuman.edit', $item->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                                    Edit
                                </a>
                                <form action="{{ route('laboran.pengumuman.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <p class="text-center text-gray-500 py-8">Belum ada pengumuman</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </main>
    </div>
</body>
</html>