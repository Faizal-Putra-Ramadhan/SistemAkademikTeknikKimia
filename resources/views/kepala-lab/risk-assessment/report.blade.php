<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Risk Assessment - Kepala Lab</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <style>
        .filter-section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
        }
        .filter-label {
            color: #374151;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }
        .filter-control {
            padding: 0.75rem;
            border: 2px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.95rem;
        }
        .filter-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .stats-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-box {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            text-align: center;
            border-top: 4px solid;
        }
        .stat-box.total { border-top-color: #667eea; }
        .stat-box.approved { border-top-color: #10b981; }
        .stat-box.rejected { border-top-color: #ef4444; }
        .stat-box.pending { border-top-color: #fbbf24; }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.25rem;
        }
        .stat-text {
            color: #6b7280;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .table-header {
            padding: 1.5rem;
            border-bottom: 2px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .table-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead {
            background: #f9fafb;
        }
        th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid #e5e7eb;
        }
        td {
            padding: 1rem;
            color: #4b5563;
            font-size: 0.95rem;
            border-bottom: 1px solid #f3f4f6;
        }
        tbody tr:hover {
            background: #f9fafb;
        }
        
        .status-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .status-disetujui { background: #d1fae5; color: #065f46; }
        .status-ditolak { background: #fee2e2; color: #991b1b; }
        .status-menunggu_dosen { background: #fef3c7; color: #92400e; }
        .status-menunggu_safety_officer { background: #dbeafe; color: #1e40af; }
        .status-menunggu_kepala_lab { background: #e0e7ff; color: #3730a3; }
        .status-draft { background: #e5e7eb; color: #4b5563; }
        
        .risk-badge {
            padding: 0.25rem 0.625rem;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .risk-tinggi { background: #fee2e2; color: #991b1b; }
        .risk-sedang { background: #fef3c7; color: #92400e; }
        .risk-rendah { background: #d1fae5; color: #065f46; }
        
        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            display: inline-block;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background: #5568d3;
        }
        .btn-success {
            background: #10b981;
            color: white;
        }
        .btn-success:hover {
            background: #059669;
        }
        .btn-secondary {
            background: #6b7280;
            color: white;
        }
        .btn-secondary:hover {
            background: #4b5563;
        }
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
            color: #6b7280;
        }
        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .export-buttons {
            display: flex;
            gap: 0.75rem;
        }
    </style>
</head>
<body class="h-full">

<div class="min-h-full">
   <x-kepala-lab.navbar :labs="$labs" :user="$user" />
    <x-kepala-lab.header>Dashboard</x-kepala-lab.header>

    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            
            <!-- Filter Section -->
            <!-- <div class="filter-section">
                <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 1rem;">
                    🔍 Filter Laporan
                </h3>
                
                <form method="GET" action="{{ route('kepala-lab.risk-assessment.report') }}">
                    <div class="filter-grid">
                        <div class="filter-group">
                            <label class="filter-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="filter-control" value="{{ request('start_date') }}">
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Tanggal Akhir</label>
                            <input type="date" name="end_date" class="filter-control" value="{{ request('end_date') }}">
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Status</label>
                            <select name="status" class="filter-control">
                                <option value="all">Semua Status</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="menunggu_dosen" {{ request('status') == 'menunggu_dosen' ? 'selected' : '' }}>Menunggu Dosen</option>
                                <option value="menunggu_safety_officer" {{ request('status') == 'menunggu_safety_officer' ? 'selected' : '' }}>Menunggu Safety Officer</option>
                                <option value="menunggu_kepala_lab" {{ request('status') == 'menunggu_kepala_lab' ? 'selected' : '' }}>Menunggu Kepala Lab</option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Laboratorium</label>
                            <select name="lab_id" class="filter-control">
                                <option value="all">Semua Lab</option>
                                @foreach(App\Models\DaftarLab::all() as $lab)
                                <option value="{{ $lab->id }}" {{ request('lab_id') == $lab->id ? 'selected' : '' }}>
                                    {{ $lab->nama_lab }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Kategori Resiko</label>
                            <select name="kategori_resiko" class="filter-control">
                                <option value="all">Semua Kategori</option>
                                <option value="tinggi" {{ request('kategori_resiko') == 'tinggi' ? 'selected' : '' }}>Beresiko Tinggi</option>
                                <option value="sedang" {{ request('kategori_resiko') == 'sedang' ? 'selected' : '' }}>Beresiko Sedang</option>
                                <option value="rendah" {{ request('kategori_resiko') == 'rendah' ? 'selected' : '' }}>Beresiko Rendah</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Jenis RA</label>
                            <select name="jenis_ra" class="filter-control">
                                <option value="all">Semua Jenis</option>
                                <option value="Penelitian" {{ request('jenis_ra') == 'Penelitian' ? 'selected' : '' }}>Penelitian</option>
                                <option value="Praktikum" {{ request('jenis_ra') == 'Praktikum' ? 'selected' : '' }}>Praktikum</option>
                                <option value="Lain-lain" {{ request('jenis_ra') == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                            </select>
                        </div>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                        <button type="submit" class="btn btn-primary">
                            🔍 Terapkan Filter
                        </button>
                        <a href="{{ route('kepala-lab.risk-assessment.report') }}" class="btn btn-secondary">
                            🔄 Reset Filter
                        </a>
                    </div>
                </form>
            </div> -->

            <!-- Summary Statistics -->
            <div class="stats-summary">
                <div class="stat-box total">
                    <div class="stat-number">{{ $riskAssessments->total() }}</div>
                    <div class="stat-text">Total Data</div>
                </div>
                <div class="stat-box approved">
                    <div class="stat-number">
                        {{ $riskAssessments->where('status', 'disetujui')->count() }}
                    </div>
                    <div class="stat-text">Disetujui</div>
                </div>
                <div class="stat-box rejected">
                    <div class="stat-number">
                        {{ $riskAssessments->where('status', 'ditolak')->count() }}
                    </div>
                    <div class="stat-text">Ditolak</div>
                </div>
                <div class="stat-box pending">
                    <div class="stat-number">
                        {{ $riskAssessments->whereIn('status', ['menunggu_dosen', 'menunggu_safety_officer', 'menunggu_kepala_lab'])->count() }}
                    </div>
                    <div class="stat-text">Dalam Proses</div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="table-container">
                <!-- <div class="table-header">
                    <h3 class="table-title">📊 Data Risk Assessment</h3>
                    <div class="export-buttons">
                        <button onclick="window.print()" class="btn btn-success btn-sm">
                            🖨️ Print
                        </button>
                        <button onclick="exportToExcel()" class="btn btn-success btn-sm">
                            📥 Export Excel
                        </button>
                        <button onclick="exportToPDF()" class="btn btn-danger btn-sm">
                            📄 Export PDF
                        </button>
                    </div> -->
                </div>

                @if($riskAssessments->count() > 0)
                <div style="overflow-x: auto;">
                    <table id="reportTable">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Mahasiswa</th>
                                <th>Judul</th>
                                <th>Lab</th>
                                <th>Jenis</th>
                                <th>Dosen</th>
                                <th>Resiko</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($riskAssessments as $index => $ra)
                            <tr>
                                <td>{{ $riskAssessments->firstItem() + $index }}</td>
                                <td>
                                    <div style="font-weight: 600;">{{ $ra->nama }}</div>
                                    <div style="font-size: 0.85rem; color: #6b7280;">{{ $ra->nim }}</div>
                                </td>
                                <td>
                                    <div style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ $ra->topik_judul }}
                                    </div>
                                </td>
                                <td>{{ $ra->daftarLab->nama_lab }}</td>
                                <td>
                                    <span style="font-size: 0.85rem; color: #6b7280;">
                                        {{ $ra->jenis_ra }}
                                    </span>
                                </td>
                                <td>{{ $ra->dosen_pembimbing_nama }}</td>
                                <td>
                                    @if($ra->kategori_resiko_dosen)
                                    <span class="risk-badge risk-{{ $ra->kategori_resiko_dosen }}">
                                        {{ ucfirst($ra->kategori_resiko_dosen) }}
                                    </span>
                                    @else
                                    <span style="color: #9ca3af;">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="status-badge status-{{ str_replace(' ', '_', $ra->status) }}">
                                        @switch($ra->status)
                                            @case('draft') Draft @break
                                            @case('menunggu_dosen') Dosen @break
                                            @case('menunggu_safety_officer') Safety @break
                                            @case('menunggu_kepala_lab') Kepala Lab @break
                                            @case('disetujui') Disetujui @break
                                            @case('ditolak') Ditolak @break
                                        @endswitch
                                    </span>
                                </td>
                                <td>
                                    <div style="font-size: 0.85rem;">
                                        {{ $ra->created_at->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('kepala-lab.risk-assessment.show', $ra->id) }}" class="btn btn-primary btn-sm" style="padding: 0.375rem 0.75rem; font-size: 0.8rem;">
                                            👁️ Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div style="padding: 1.5rem; border-top: 2px solid #e5e7eb;">
                    {{ $riskAssessments->links() }}
                </div>
                @else
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <p style="font-size: 1.1rem; font-weight: 500;">Tidak ada data yang sesuai dengan filter</p>
                    <p style="margin-top: 0.5rem;">Coba ubah kriteria filter atau reset filter</p>
                </div>
                @endif
            </div>

        </div>
    </main>
</div>

<script>
// Export to Excel (Simple CSV)
function exportToExcel() {
    let csv = [];
    const rows = document.querySelectorAll('#reportTable tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = [], cols = rows[i].querySelectorAll('td, th');
        
        for (let j = 0; j < cols.length - 1; j++) { // Skip last column (action)
            let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, ' ').trim();
            row.push('"' + data + '"');
        }
        
        csv.push(row.join(','));
    }
    
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    link.setAttribute('href', url);
    link.setAttribute('download', 'laporan_risk_assessment_' + new Date().getTime() + '.csv');
    link.style.visibility = 'hidden';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Export to PDF
function exportToPDF() {
    alert('Fitur Export PDF memerlukan library seperti jsPDF atau server-side PDF generator.\n\nUntuk sekarang, gunakan fungsi Print (Ctrl+P) dan pilih "Save as PDF".');
    window.print();
}

// Print styles
const style = document.createElement('style');
style.textContent = `
    @media print {
        nav, header, .filter-section, .export-buttons, .action-buttons, .btn {
            display: none !important;
        }
        .table-container {
            box-shadow: none;
        }
        body {
            background: white;
        }
    }
`;
document.head.appendChild(style);
</script>

<script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
</body>
</html>