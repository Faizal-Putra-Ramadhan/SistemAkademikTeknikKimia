@extends('layouts.app')

@section('title', 'Laporan Risk Assessment')
@section('page-title', 'Laporan Risk Assessment')

@push('styles')
<style>
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
        border: 1px solid #e5e7eb;
        text-align: center;
        border-top: 4px solid;
    }
    .stat-box.total { border-top-color: #0d6efd; }
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
    
    .risk-badge {
        padding: 0.25rem 0.625rem;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .risk-tinggi { background: #fee2e2; color: #991b1b; }
    .risk-sedang { background: #fef3c7; color: #92400e; }
    .risk-rendah { background: #d1fae5; color: #065f46; }
    
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
</style>
@endpush

@section('content')
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
    <div class="card">
        <div class="card-header">
            <h3>📊 Data Risk Assessment</h3>
        </div>
        <div class="card-body">
            @if($riskAssessments->count() > 0)
            <div class="table-wrapper">
                <table class="data-table" id="reportTable">
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
                                <span class="badge {{ $ra->status == 'disetujui' ? 'badge-success' : ($ra->status == 'ditolak' ? 'badge-danger' : ($ra->status == 'draft' ? 'badge-secondary' : ($ra->status == 'menunggu_kepala_lab' ? 'badge-info' : 'badge-warning'))) }}">
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
                                <a href="{{ route('kepala-lab.risk-assessment.show', $ra->id) }}" class="btn btn-primary btn-sm">
                                    👁️ Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div style="padding-top: 1.5rem; border-top: 2px solid #e5e7eb; margin-top: 1rem;">
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
@endsection

@push('scripts')
<script>
// Export to Excel (Simple CSV)
function exportToExcel() {
    let csv = [];
    const rows = document.querySelectorAll('#reportTable tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = [], cols = rows[i].querySelectorAll('td, th');
        
        for (let j = 0; j < cols.length - 1; j++) {
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
        .app-sidebar, .app-topbar, .export-buttons, .btn {
            display: none !important;
        }
        .app-content { margin-left: 0 !important; padding-top: 0 !important; }
        body { background: white; }
    }
`;
document.head.appendChild(style);
</script>
@endpush