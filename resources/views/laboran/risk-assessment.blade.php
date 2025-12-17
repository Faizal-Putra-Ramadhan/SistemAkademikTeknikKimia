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
    <div class="min-h-full">
        <x-laboran.navbar :labs="$labs" :user="$user"></x-laboran.navbar>
        <x-laboran.header>Risk Assessment</x-laboran.header>

        <main>
            <div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg">
        <!-- Header -->
        <div class="border-b px-6 py-4">
            <h1 class="text-2xl font-bold text-gray-800">Risk Assessment</h1>
            <p class="text-sm text-gray-600 mt-1">{{ $lab->Nama_Laboratorium }}</p>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Filter Tabs -->
        <div class="border-b px-6 pt-4">
            <div class="flex space-x-4">
                <button onclick="filterStatus('all')" class="filter-tab px-4 py-2 font-medium text-gray-600 hover:text-gray-800 border-b-2 border-transparent hover:border-gray-300 active" data-status="all">
                    Semua ({{ $riskAssessments->count() }})
                </button>
                <button onclick="filterStatus('draft')" class="filter-tab px-4 py-2 font-medium text-gray-600 hover:text-gray-800 border-b-2 border-transparent hover:border-gray-300" data-status="draft">
                    Draft ({{ $riskAssessments->where('status', 'draft')->count() }})
                </button>
                <button onclick="filterStatus('menunggu_dosen')" class="filter-tab px-4 py-2 font-medium text-gray-600 hover:text-gray-800 border-b-2 border-transparent hover:border-gray-300" data-status="menunggu_dosen">
                    Menunggu Dosen ({{ $riskAssessments->where('status', 'menunggu_dosen')->count() }})
                </button>
                <button onclick="filterStatus('menunggu_safety_officer')" class="filter-tab px-4 py-2 font-medium text-gray-600 hover:text-gray-800 border-b-2 border-transparent hover:border-gray-300" data-status="menunggu_safety_officer">
                    Menunggu SO ({{ $riskAssessments->where('status', 'menunggu_safety_officer')->count() }})
                </button>
                <button onclick="filterStatus('menunggu_kepala_lab')" class="filter-tab px-4 py-2 font-medium text-gray-600 hover:text-gray-800 border-b-2 border-transparent hover:border-gray-300" data-status="menunggu_kepala_lab">
                    Menunggu Kepala Lab ({{ $riskAssessments->where('status', 'menunggu_kepala_lab')->count() }})
                </button>
                <button onclick="filterStatus('disetujui')" class="filter-tab px-4 py-2 font-medium text-green-600 hover:text-green-800 border-b-2 border-transparent hover:border-green-300" data-status="disetujui">
                    Disetujui ({{ $riskAssessments->where('status', 'disetujui')->count() }})
                </button>
                <button onclick="filterStatus('ditolak')" class="filter-tab px-4 py-2 font-medium text-red-600 hover:text-red-800 border-b-2 border-transparent hover:border-red-300" data-status="ditolak">
                    Ditolak ({{ $riskAssessments->where('status', 'ditolak')->count() }})
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="p-6">
            @if($riskAssessments->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada Risk Assessment</h3>
                    <p class="mt-1 text-sm text-gray-500">Belum ada mahasiswa yang mengajukan Risk Assessment untuk lab ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis RA</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Topik/Judul</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen Pembimbing</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($riskAssessments as $index => $ra)
                                <tr class="ra-row hover:bg-gray-50" data-status="{{ $ra->status }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $ra->nama }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ra->nim }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($ra->jenis_ra === 'Penelitian') bg-blue-100 text-blue-800
                                            @elseif($ra->jenis_ra === 'Praktikum') bg-purple-100 text-purple-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $ra->jenis_ra }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs truncate">{{ $ra->topik_judul ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $ra->dosen_pembimbing_nama ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusConfig = [
                                                'draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Draft'],
                                                'menunggu_dosen' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Menunggu Dosen'],
                                                'menunggu_safety_officer' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Menunggu SO'],
                                                'menunggu_kepala_lab' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'label' => 'Menunggu Kepala Lab'],
                                                'disetujui' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Disetujui'],
                                                'ditolak' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Ditolak'],
                                            ];
                                            $config = $statusConfig[$ra->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => $ra->status];
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $config['bg'] }} {{ $config['text'] }}">
                                            {{ $config['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $ra->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('laboran.risk-assessment.detail', $ra->id) }}" 
                                           class="inline-flex items-center px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function filterStatus(status) {
    const rows = document.querySelectorAll('.ra-row');
    const tabs = document.querySelectorAll('.filter-tab');
    
    // Update active tab
    tabs.forEach(tab => {
        if (tab.dataset.status === status) {
            tab.classList.add('active', 'border-blue-500', 'text-blue-600');
            tab.classList.remove('border-transparent', 'text-gray-600');
        } else {
            tab.classList.remove('active', 'border-blue-500', 'text-blue-600');
            tab.classList.add('border-transparent', 'text-gray-600');
        }
    });
    
    // Filter rows
    rows.forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Set initial active state
document.addEventListener('DOMContentLoaded', function() {
    const firstTab = document.querySelector('.filter-tab[data-status="all"]');
    if (firstTab) {
        firstTab.classList.add('border-blue-500', 'text-blue-600');
    }
});
</script>
        </main>
    </div>
</body>
</html>