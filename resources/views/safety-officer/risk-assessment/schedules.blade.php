<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
</head>
<body>
     <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <div class="min-h-full">
        <x-safety-officer.navbar :labs="$labs" :user="$user"></x-safety-officer.navbar>
        <x-safety-officer.header>Kelola Jadwal</x-safety-officer.header>

        <main>
            <div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Jadwal Wawancara</h1>
            <p class="mt-2 text-sm text-gray-600">Kelola jadwal wawancara dengan mahasiswa</p>
        </div>

        <!-- Upcoming Schedules -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Jadwal Mendatang</h2>
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @forelse($upcomingSchedules as $schedule)
                    <li>
                        <div class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <svg class="h-10 w-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-indigo-600">
                                                {{ $schedule->jadwal_wawancara->format('l, d F Y') }}
                                            </p>
                                            <p class="text-lg font-semibold text-gray-900">
                                                {{ $schedule->jadwal_wawancara->format('H:i') }} WIB
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    @php
                                        $now = now();
                                        $diffInHours = $now->diffInHours($schedule->jadwal_wawancara, false);
                                    @endphp
                                    @if($diffInHours <= 24 && $diffInHours > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="mr-1.5 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            Segera ({{ $schedule->jadwal_wawancara->diffForHumans() }})
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $schedule->jadwal_wawancara->diffForHumans() }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $schedule->nama }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            NIM: {{ $schedule->nim }}
                                        </p>
                                        <p class="mt-1 text-sm text-gray-700">
                                            <span class="font-medium">Topik:</span> {{ $schedule->topik_judul }}
                                        </p>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Lab: {{ $schedule->daftarLab->Nama_Laboratorium }}
                                        </p>
                                        @if($schedule->catatan_safety_officer)
                                        <p class="mt-2 text-sm text-gray-600">
                                            <span class="font-medium">Catatan:</span> {{ $schedule->catatan_safety_officer }}
                                        </p>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('safety-officer.risk-assessment.show', $schedule->id) }}" 
                                           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Lihat Detail
                                            <svg class="ml-2 -mr-0.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="px-4 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada jadwal mendatang</h3>
                        <p class="mt-1 text-sm text-gray-500">Belum ada wawancara yang dijadwalkan.</p>
                    </li>
                    @endforelse
                </ul>
            </div>
            
            @if($upcomingSchedules->hasPages())
            <div class="mt-4">
                {{ $upcomingSchedules->links() }}
            </div>
            @endif
        </div>

        <!-- Past Schedules -->
        <div>
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Riwayat Wawancara</h2>
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @forelse($pastSchedules as $schedule)
                    <li>
                        <div class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $schedule->nama }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $schedule->topik_judul }}
                                    </p>
                                </div>
                                <div class="ml-4 flex-shrink-0 flex flex-col items-end">
                                    <p class="text-sm text-gray-500">
                                        {{ $schedule->jadwal_wawancara->format('d M Y, H:i') }}
                                    </p>
                                    @if($schedule->persetujuan_safety_officer === true)
                                        <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Disetujui
                                        </span>
                                    @elseif($schedule->persetujuan_safety_officer === false)
                                        <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Belum Review
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="px-4 py-8 text-center">
                        <p class="text-sm text-gray-500">Tidak ada riwayat wawancara.</p>
                    </li>
                    @endforelse
                </ul>
            </div>
            
            @if($pastSchedules->hasPages())
            <div class="mt-4">
                {{ $pastSchedules->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
        </main>

    </div>
    
</body>
</html>