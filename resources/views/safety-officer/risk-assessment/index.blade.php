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
        <x-safety-officer.header>Risk Assessment</x-safety-officer.header>

        <main>
            <div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Risk Assessment - Review</h1>
            <p class="mt-2 text-sm text-gray-600">Tinjau dan setujui Risk Assessment dari mahasiswa</p>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Tabs -->
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button onclick="showTab('pending')" id="tab-pending" class="tab-button border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Menunggu Review
                        @if($riskAssessments->total() > 0)
                            <span class="ml-2 bg-indigo-100 text-indigo-600 py-0.5 px-2.5 rounded-full text-xs font-medium">
                                {{ $riskAssessments->total() }}
                            </span>
                        @endif
                    </button>
                    <button onclick="showTab('history')" id="tab-history" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Riwayat
                    </button>
                </nav>
            </div>
        </div>

        <!-- Tab Content: Pending -->
        <div id="content-pending" class="tab-content">
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @forelse($riskAssessments as $ra)
                    <li>
                        <a href="{{ route('safety-officer.risk-assessment.show', $ra->id) }}" class="block hover:bg-gray-50">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-indigo-600 truncate">
                                            {{ $ra->topik_judul }}
                                        </p>
                                        <p class="mt-2 flex items-center text-sm text-gray-500">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $ra->nama }} ({{ $ra->nim }})
                                        </p>
                                    </div>
                                    <div class="ml-2 flex-shrink-0 flex flex-col items-end">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Menunggu Review
                                        </span>
                                        <p class="mt-2 text-sm text-gray-500">
                                            Lab: {{ $ra->daftarLab->Nama_Laboratorium }}
                                        </p>
                                        <p class="mt-1 text-xs text-gray-400">
                                            Dosen: {{ $ra->dosenPembimbing->Nama ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <p class="flex items-center text-sm text-gray-500">
                                            Jenis: <span class="ml-1 font-medium">{{ $ra->jenis_ra }}</span>
                                        </p>
                                        @if($ra->kategori_resiko_dosen)
                                        <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                            Risiko (Dosen): 
                                            <span class="ml-1 px-2 py-1 text-xs font-semibold rounded
                                                {{ $ra->kategori_resiko_dosen === 'tinggi' ? 'bg-red-100 text-red-800' : '' }}
                                                {{ $ra->kategori_resiko_dosen === 'sedang' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $ra->kategori_resiko_dosen === 'rendah' ? 'bg-green-100 text-green-800' : '' }}">
                                                {{ ucfirst($ra->kategori_resiko_dosen) }}
                                            </span>
                                        </p>
                                        @endif
                                    </div>
                                    <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                        </svg>
                                        <p>Diajukan {{ $ra->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    @empty
                    <li class="px-4 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada Risk Assessment</h3>
                        <p class="mt-1 text-sm text-gray-500">Belum ada Risk Assessment yang menunggu review Anda.</p>
                    </li>
                    @endforelse
                </ul>
            </div>

            @if($riskAssessments->hasPages())
            <div class="mt-4">
                {{ $riskAssessments->links() }}
            </div>
            @endif
        </div>

        <!-- Tab Content: History -->
        <div id="content-history" class="tab-content hidden">
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @forelse($riwayat as $ra)
                    <li>
                        <a href="{{ route('safety-officer.risk-assessment.show', $ra->id) }}" class="block hover:bg-gray-50">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-indigo-600 truncate">
                                            {{ $ra->topik_judul }}
                                        </p>
                                        <p class="mt-2 flex items-center text-sm text-gray-500">
                                            {{ $ra->nama }} ({{ $ra->nim }})
                                        </p>
                                    </div>
                                    <div class="ml-2 flex-shrink-0">
                                        @if($ra->status === 'menunggu_kepala_lab')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Menunggu Kepala Lab
                                            </span>
                                        @elseif($ra->status === 'disetujui')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Disetujui
                                            </span>
                                        @elseif($ra->status === 'ditolak')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Ditolak
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ ucfirst(str_replace('_', ' ', $ra->status)) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex space-x-4">
                                        <p class="text-sm text-gray-500">
                                            Lab: {{ $ra->daftarLab->Nama_Laboratorium }}
                                        </p>
                                        @if($ra->tanggal_persetujuan_safety_officer)
                                        <p class="text-sm text-gray-500">
                                            Direview: {{ $ra->tanggal_persetujuan_safety_officer->format('d M Y') }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    @empty
                    <li class="px-4 py-12 text-center">
                        <p class="text-sm text-gray-500">Belum ada riwayat review.</p>
                    </li>
                    @endforelse
                </ul>
            </div>

            @if($riwayat->hasPages())
            <div class="mt-4">
                {{ $riwayat->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('border-indigo-500', 'text-indigo-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Add active class to selected button
    const activeButton = document.getElementById('tab-' + tabName);
    activeButton.classList.add('border-indigo-500', 'text-indigo-600');
    activeButton.classList.remove('border-transparent', 'text-gray-500');
}
</script>
        </main>
    </div>
</body>
</html>