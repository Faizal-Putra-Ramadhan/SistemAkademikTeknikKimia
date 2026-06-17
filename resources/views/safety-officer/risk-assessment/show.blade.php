@extends('layouts.app')
@section('title', 'Detail Risk Assessment')
@section('page-title', 'Detail Risk Assessment')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* ===== Detail RA - Safety Officer ===== */
    .det-page { max-width: 960px; margin: 0 auto; }

    /* Back link */
    .det-back {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 13px; font-weight: 500; color: #059669;
        text-decoration: none; margin-bottom: 20px;
        transition: color 0.15s;
    }
    .det-back:hover { color: #047857; }
    .det-back i { font-size: 12px; }

    /* Alerts */
    .det-alert {
        display: flex; align-items: flex-start; gap: 10px;
        padding: 12px 16px; border-radius: 10px;
        margin-bottom: 16px; font-size: 13.5px; font-weight: 500;
    }
    .det-alert.success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; }
    .det-alert.danger  { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
    .det-alert i { font-size: 16px; flex-shrink: 0; margin-top: 1px; }
    .det-alert ul { margin: 4px 0 0; padding-left: 16px; }
    .det-alert li { font-size: 13px; }

    /* Title card */
    .det-title-card {
        background: #fff; border: 1px solid #e5e7eb; border-radius: 12px;
        padding: 24px; margin-bottom: 20px;
    }
    .det-title-top {
        display: flex; justify-content: space-between; align-items: flex-start;
        gap: 16px; margin-bottom: 8px;
    }
    .det-title-card h1 { font-size: 20px; font-weight: 700; color: #111827; line-height: 1.4; }
    .det-title-card .det-id { font-size: 12.5px; color: #9ca3af; margin-top: 2px; }

    /* Badge */
    .det-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 12px; border-radius: 20px;
        font-size: 12px; font-weight: 600; white-space: nowrap;
    }
    .det-badge.pending { background: #fef3c7; color: #92400e; }
    .det-badge.success { background: #d1fae5; color: #065f46; }
    .det-badge.danger  { background: #fee2e2; color: #991b1b; }
    .det-badge.info    { background: #dbeafe; color: #1e40af; }

    /* Timeline */
    .det-timeline { padding-top: 20px; border-top: 1px solid #f3f4f6; }
    .det-timeline h3 {
        font-size: 13px; font-weight: 700; color: #6b7280;
        text-transform: uppercase; letter-spacing: 0.5px;
        margin-bottom: 16px;
    }
    .det-tl-list { list-style: none; padding: 0; margin: 0; }
    .det-tl-item {
        display: flex; gap: 14px;
        position: relative;
        padding-bottom: 20px;
    }
    .det-tl-item:last-child { padding-bottom: 0; }
    .det-tl-item:not(:last-child)::after {
        content: '';
        position: absolute; left: 15px; top: 34px;
        width: 2px; bottom: 0;
        background: #e5e7eb;
    }
    .det-tl-dot {
        width: 32px; height: 32px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; flex-shrink: 0;
        border: 3px solid #fff;
        box-shadow: 0 0 0 2px #e5e7eb;
        position: relative; z-index: 1;
    }
    .det-tl-dot.done    { background: #059669; color: #fff; box-shadow: 0 0 0 2px #059669; }
    .det-tl-dot.reject  { background: #dc2626; color: #fff; box-shadow: 0 0 0 2px #dc2626; }
    .det-tl-dot.waiting { background: #d1d5db; color: #fff; box-shadow: 0 0 0 2px #d1d5db; }
    .det-tl-body { flex: 1; min-width: 0; padding-top: 4px; }
    .det-tl-title { font-size: 13.5px; font-weight: 600; color: #111827; }
    .det-tl-desc  { font-size: 12.5px; color: #6b7280; margin-top: 2px; }
    .det-tl-date  { font-size: 12px; color: #9ca3af; margin-top: 2px; }

    .det-risk-tag {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 2px 8px; border-radius: 6px;
        font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.3px;
    }
    .det-risk-tag.tinggi { background: #fee2e2; color: #991b1b; }
    .det-risk-tag.sedang { background: #fef3c7; color: #92400e; }
    .det-risk-tag.rendah { background: #d1fae5; color: #065f46; }

    /* Section Card */
    .det-section {
        background: #fff; border: 1px solid #e5e7eb; border-radius: 12px;
        overflow: hidden; margin-bottom: 20px;
    }
    .det-section-head {
        padding: 16px 24px; border-bottom: 1px solid #e5e7eb;
        display: flex; align-items: center; gap: 10px;
    }
    .det-section-head i { color: #059669; font-size: 15px; }
    .det-section-head h2 { font-size: 15px; font-weight: 700; color: #1f2937; }
    .det-section-body { padding: 20px 24px; }

    /* Data grid */
    .det-grid {
        display: grid; grid-template-columns: repeat(2, 1fr);
        gap: 18px;
    }
    @media (max-width: 640px) { .det-grid { grid-template-columns: 1fr; } }
    .det-grid.full { grid-template-columns: 1fr; }

    .det-field label {
        display: block; font-size: 11.5px; font-weight: 600;
        color: #6b7280; text-transform: uppercase; letter-spacing: 0.4px;
        margin-bottom: 4px;
    }
    .det-field p { font-size: 13.5px; color: #111827; font-weight: 500; }
    .det-field.span-2 { grid-column: 1 / -1; }

    /* Bahan kimia card */
    .det-bahan {
        border: 1px solid #f3f4f6; border-radius: 10px;
        padding: 14px 18px; margin-bottom: 12px;
    }
    .det-bahan:last-child { margin-bottom: 0; }
    .det-bahan h4 { font-size: 14px; font-weight: 600; color: #111827; margin-bottom: 8px; }
    .det-bahan-tags { display: flex; flex-wrap: wrap; gap: 6px; }
    .det-bahan-tag {
        padding: 3px 10px; border-radius: 6px;
        font-size: 11px; font-weight: 600;
    }
    .det-bahan-tag.red    { background: #fee2e2; color: #991b1b; }
    .det-bahan-tag.orange { background: #ffedd5; color: #9a3412; }
    .det-bahan-tag.purple { background: #f3e8ff; color: #6b21a8; }
    .det-bahan-tag.yellow { background: #fef3c7; color: #92400e; }
    .det-bahan-tag.pink   { background: #fce7f3; color: #9d174d; }
    .det-bahan-tag.blue   { background: #dbeafe; color: #1e40af; }
    .det-bahan-note { font-size: 12.5px; color: #6b7280; margin-top: 8px; }

    /* Hazard box */
    .det-hazard-box {
        background: #f9fafb; border-radius: 10px; padding: 14px 18px;
        margin-top: 16px;
        display: flex; align-items: center; justify-content: space-between;
    }
    .det-hazard-box span.label { font-size: 13px; font-weight: 600; color: #374151; }
    .det-hazard-pill {
        padding: 4px 14px; border-radius: 20px;
        font-size: 12px; font-weight: 700;
    }
    .det-hazard-pill.sangat_hazardous { background: #fee2e2; color: #991b1b; }
    .det-hazard-pill.hazardous        { background: #ffedd5; color: #9a3412; }
    .det-hazard-pill.moderat          { background: #fef3c7; color: #92400e; }
    .det-hazard-pill.tidak_hazardous  { background: #d1fae5; color: #065f46; }

    /* Checklist */
    .det-check-list { list-style: none; padding: 0; margin: 0; }
    .det-check-item {
        display: flex; align-items: center; gap: 10px;
        padding: 8px 0; border-bottom: 1px solid #f9fafb;
        font-size: 13.5px; color: #374151;
    }
    .det-check-item:last-child { border-bottom: none; }
    .det-check-icon {
        width: 22px; height: 22px; border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; flex-shrink: 0;
    }
    .det-check-icon.yes { background: #d1fae5; color: #059669; }
    .det-check-icon.no  { background: #fee2e2; color: #dc2626; }

    /* Skill box */
    .det-skill-box {
        background: #f9fafb; border-radius: 10px; padding: 14px 18px;
        margin-top: 12px;
        display: flex; align-items: center; justify-content: space-between;
    }
    .det-skill-box span.label { font-size: 13px; font-weight: 600; color: #374151; }
    .det-skill-pill {
        padding: 4px 14px; border-radius: 20px;
        font-size: 12px; font-weight: 700;
    }
    .det-skill-pill.ceroboh         { background: #fee2e2; color: #991b1b; }
    .det-skill-pill.kurang_terampil { background: #fef3c7; color: #92400e; }
    .det-skill-pill.cukup_terampil  { background: #dbeafe; color: #1e40af; }
    .det-skill-pill.sangat_terampil { background: #d1fae5; color: #065f46; }

    /* Notes */
    .det-note {
        padding: 14px 18px; border-radius: 10px; margin-bottom: 10px;
    }
    .det-note:last-child { margin-bottom: 0; }
    .det-note.blue   { background: #eff6ff; border-left: 3px solid #3b82f6; }
    .det-note.amber  { background: #fffbeb; border-left: 3px solid #f59e0b; }
    .det-note .note-label { font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 4px; }
    .det-note p { font-size: 13px; color: #4b5563; line-height: 1.6; }

    /* Action Card */
    .det-action-card {
        background: #fff; border: 2px solid #059669; border-radius: 12px;
        overflow: hidden; margin-bottom: 20px;
    }
    .det-action-head {
        padding: 16px 24px; background: #ecfdf5;
        border-bottom: 1px solid #a7f3d0;
        display: flex; align-items: center; gap: 10px;
    }
    .det-action-head i { color: #059669; font-size: 16px; }
    .det-action-head h2 { font-size: 15px; font-weight: 700; color: #065f46; }
    .det-action-body { padding: 24px; }

    /* Schedule section */
    .det-sched-box {
        background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px;
        padding: 20px; margin-bottom: 24px;
    }
    .det-sched-box h3 {
        font-size: 14px; font-weight: 700; color: #1e40af;
        margin-bottom: 12px; display: flex; align-items: center; gap: 8px;
    }
    .det-sched-box h3 i { font-size: 14px; }
    .det-sched-desc { font-size: 13px; color: #3b82f6; margin-bottom: 16px; }

    /* Schedule confirmed */
    .det-sched-confirmed {
        background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 10px;
        padding: 20px; margin-bottom: 24px;
    }
    .det-sched-confirmed h3 {
        font-size: 14px; font-weight: 700; color: #065f46;
        margin-bottom: 12px; display: flex; align-items: center; gap: 8px;
    }
    .det-sched-table { width: 100%; }
    .det-sched-table td { padding: 5px 0; font-size: 13px; }
    .det-sched-table td:first-child { color: #6b7280; width: 130px; font-weight: 500; }
    .det-sched-table td:last-child { color: #111827; font-weight: 600; }
    .det-sched-note { font-size: 13px; color: #047857; margin-top: 12px; }

    /* Option card */
    .det-opt-card {
        border: 1px solid #e5e7eb; border-radius: 10px;
        padding: 16px 18px; margin-bottom: 12px; background: #fff;
    }
    .det-opt-head {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 12px;
    }
    .det-opt-label { font-size: 13px; font-weight: 700; color: #374151; }
    .det-opt-remove {
        background: none; border: none; cursor: pointer;
        font-size: 12px; font-weight: 500; color: #dc2626;
        display: inline-flex; align-items: center; gap: 4px;
    }
    .det-opt-remove:hover { text-decoration: underline; }

    /* Form styles */
    .det-form-row {
        display: grid; grid-template-columns: 1fr 1fr; gap: 14px;
    }
    @media (max-width: 640px) { .det-form-row { grid-template-columns: 1fr; } }
    .det-form-group { margin-bottom: 14px; }
    .det-form-group label {
        display: block; font-size: 12.5px; font-weight: 600;
        color: #374151; margin-bottom: 5px;
    }
    .det-form-input {
        width: 100%; padding: 9px 12px; border: 1px solid #d1d5db;
        border-radius: 8px; font-size: 13.5px; color: #1f2937;
        transition: border-color 0.15s;
    }
    .det-form-input:focus { outline: none; border-color: #059669; box-shadow: 0 0 0 3px rgba(5,150,105,0.1); }
    .det-form-textarea {
        width: 100%; padding: 10px 12px; border: 1px solid #d1d5db;
        border-radius: 8px; font-size: 13.5px; color: #1f2937;
        resize: vertical; min-height: 100px;
        transition: border-color 0.15s;
    }
    .det-form-textarea:focus { outline: none; border-color: #059669; box-shadow: 0 0 0 3px rgba(5,150,105,0.1); }

    /* Radio */
    .det-radio-group { display: flex; flex-direction: column; gap: 10px; }
    .det-radio-item {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 16px; border: 1px solid #e5e7eb; border-radius: 10px;
        cursor: pointer; transition: all 0.15s;
    }
    .det-radio-item:hover { background: #f9fafb; }
    .det-radio-item input[type="radio"] { width: 16px; height: 16px; accent-color: #059669; }
    .det-radio-label { font-size: 13.5px; color: #374151; }
    .det-radio-label strong { font-weight: 600; color: #111827; }

    /* Buttons */
    .det-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 10px 20px; border-radius: 8px;
        font-size: 13.5px; font-weight: 600; border: none;
        cursor: pointer; transition: all 0.15s;
        text-decoration: none;
    }
    .det-btn.primary  { background: #059669; color: #fff; }
    .det-btn.primary:hover { background: #047857; }
    .det-btn.blue     { background: #2563eb; color: #fff; }
    .det-btn.blue:hover { background: #1d4ed8; }
    .det-btn.green    { background: #16a34a; color: #fff; }
    .det-btn.green:hover { background: #15803d; }
    .det-btn.amber    { background: #d97706; color: #fff; }
    .det-btn.amber:hover { background: #b45309; }
    .det-btn.outline  { background: #fff; color: #374151; border: 1px solid #d1d5db; }
    .det-btn.outline:hover { background: #f9fafb; }
    .det-btn-row { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 20px; }

    /* Revision box */
    .det-revision-box {
        background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px;
        padding: 20px; margin-top: 20px; display: none;
    }
    .det-revision-box.show { display: block; }
    .det-revision-box h3 {
        font-size: 14px; font-weight: 700; color: #92400e;
        margin-bottom: 12px; display: flex; align-items: center; gap: 8px;
    }

    /* Helper text */
    .det-hint { font-size: 11.5px; color: #9ca3af; margin-top: 4px; }

    /* Separator */
    .det-divider { border: none; border-top: 1px solid #f3f4f6; margin: 20px 0; }
</style>
@endpush

@section('content')
<div class="det-page">

    {{-- Back --}}
    <a href="{{ route('safety-officer.risk-assessment.index') }}" class="det-back">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar RA
    </a>

    {{-- Alerts --}}
    @if(session('success'))
    <div class="det-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if($errors->any())
    <div class="det-alert danger">
        <i class="fas fa-exclamation-circle"></i>
        <div><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    </div>
    @endif

    {{-- ============ TITLE + STATUS ============ --}}
    <div class="det-title-card">
        <div class="det-title-top">
            <div>
                <h1>{{ $riskAssessment->topik_judul }}</h1>
                <div class="det-id">Risk Assessment #{{ $riskAssessment->id }}</div>
            </div>
            @if($riskAssessment->status === 'menunggu_safety_officer')
                <span class="det-badge pending"><i class="fas fa-clock"></i> Menunggu Review Anda</span>
            @elseif($riskAssessment->status === 'menunggu_kepala_lab')
                <span class="det-badge info"><i class="fas fa-hourglass-half"></i> Menunggu Kepala Lab</span>
            @elseif($riskAssessment->status === 'disetujui')
                <span class="det-badge success"><i class="fas fa-check-circle"></i> Disetujui</span>
            @elseif($riskAssessment->status === 'ditolak')
                <span class="det-badge danger"><i class="fas fa-times-circle"></i> Ditolak</span>
            @endif
        </div>

        {{-- Timeline --}}
        <div class="det-timeline">
            <h3>Status Review</h3>
            <ul class="det-tl-list">
                {{-- Dosen --}}
                <li class="det-tl-item">
                    <div class="det-tl-dot done"><i class="fas fa-check"></i></div>
                    <div class="det-tl-body">
                        <div class="det-tl-title">Dosen Pembimbing</div>
                        <div class="det-tl-desc">
                            Disetujui oleh <strong>{{ $riskAssessment->dosenPembimbing->Nama }}</strong>
                            @if($riskAssessment->kategori_resiko_dosen)
                                &mdash; Kategori Risiko:
                                <span class="det-risk-tag {{ $riskAssessment->kategori_resiko_dosen }}">
                                    {{ ucfirst($riskAssessment->kategori_resiko_dosen) }}
                                </span>
                            @endif
                        </div>
                        <div class="det-tl-date">{{ $riskAssessment->tanggal_persetujuan_dosen ? $riskAssessment->tanggal_persetujuan_dosen->format('d M Y, H:i') : '-' }}</div>
                    </div>
                </li>

                {{-- Safety Officer --}}
                <li class="det-tl-item">
                    @if($riskAssessment->persetujuan_safety_officer === true)
                        <div class="det-tl-dot done"><i class="fas fa-check"></i></div>
                    @elseif($riskAssessment->persetujuan_safety_officer === false)
                        <div class="det-tl-dot reject"><i class="fas fa-times"></i></div>
                    @else
                        <div class="det-tl-dot waiting"><i class="fas fa-clock"></i></div>
                    @endif
                    <div class="det-tl-body">
                        <div class="det-tl-title">Safety Officer Review</div>
                        <div class="det-tl-desc">
                            @if($riskAssessment->persetujuan_safety_officer === null)
                                Menunggu review
                            @else
                                {{ $riskAssessment->persetujuan_safety_officer ? 'Disetujui' : 'Ditolak' }} oleh {{ $riskAssessment->safety_officer_nama }}
                            @endif
                        </div>
                        <div class="det-tl-date">{{ $riskAssessment->tanggal_persetujuan_safety_officer ? $riskAssessment->tanggal_persetujuan_safety_officer->format('d M Y, H:i') : '-' }}</div>
                    </div>
                </li>

                {{-- Kepala Lab --}}
                <li class="det-tl-item">
                    @if($riskAssessment->persetujuan_kepala_lab === true)
                        <div class="det-tl-dot done"><i class="fas fa-check"></i></div>
                    @elseif($riskAssessment->persetujuan_kepala_lab === false)
                        <div class="det-tl-dot reject"><i class="fas fa-times"></i></div>
                    @else
                        <div class="det-tl-dot waiting"><i class="fas fa-clock"></i></div>
                    @endif
                    <div class="det-tl-body">
                        <div class="det-tl-title">Kepala Laboratorium</div>
                        <div class="det-tl-desc">
                            @if($riskAssessment->persetujuan_kepala_lab === null)
                                Menunggu persetujuan final
                            @else
                                {{ $riskAssessment->persetujuan_kepala_lab ? 'Final Approval' : 'Ditolak' }}
                            @endif
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    {{-- ============ DATA MAHASISWA ============ --}}
    <div class="det-section">
        <div class="det-section-head">
            <i class="fas fa-user-graduate"></i>
            <h2>Data Mahasiswa</h2>
        </div>
        <div class="det-section-body">
            <div class="det-grid">
                <div class="det-field">
                    <label>Nama Lengkap</label>
                    <p>{{ $riskAssessment->nama }}</p>
                </div>
                <div class="det-field">
                    <label>NIM</label>
                    <p>{{ $riskAssessment->nim }}</p>
                </div>
                <div class="det-field">
                    <label>No. Kontak</label>
                    <p>{{ $riskAssessment->no_kontak }}</p>
                </div>
                <div class="det-field">
                    <label>Alamat</label>
                    <p>{{ $riskAssessment->alamat_kontak }}</p>
                </div>
                <div class="det-field">
                    <label>Laboratorium</label>
                    <p>{{ $riskAssessment->daftarLab->Nama_Laboratorium }}</p>
                </div>
                <div class="det-field">
                    <label>Jenis Risk Assessment</label>
                    <p>{{ $riskAssessment->jenis_ra }}</p>
                </div>
                <div class="det-field span-2">
                    <label>Topik / Judul</label>
                    <p>{{ $riskAssessment->topik_judul }}</p>
                </div>
                <div class="det-field">
                    <label>Dosen Pembimbing</label>
                    <p>{{ $riskAssessment->dosen_pembimbing_nama }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ============ BAHAN KIMIA ============ --}}
    <div class="det-section">
        <div class="det-section-head">
            <i class="fas fa-vial"></i>
            <h2>Bahan Kimia yang Digunakan</h2>
        </div>
        <div class="det-section-body">
            @foreach($riskAssessment->bahanKimias as $bahan)
            <div class="det-bahan">
                <h4><i class="fas fa-atom" style="margin-right:6px;color:#6b7280;font-size:13px;"></i> {{ $bahan->nama_bahan }}</h4>
                <div class="det-bahan-tags">
                    @if($bahan->explosive)  <span class="det-bahan-tag red">Explosive</span> @endif
                    @if($bahan->flammable)  <span class="det-bahan-tag orange">Flammable</span> @endif
                    @if($bahan->toxic)      <span class="det-bahan-tag purple">Toxic</span> @endif
                    @if($bahan->corrosive)  <span class="det-bahan-tag yellow">Corrosive</span> @endif
                    @if($bahan->irritant)   <span class="det-bahan-tag pink">Irritant</span> @endif
                    @if($bahan->oxidizing)  <span class="det-bahan-tag blue">Oxidizing</span> @endif
                </div>
                @if($bahan->lain_lain)
                <div class="det-bahan-note"><i class="fas fa-info-circle" style="margin-right:4px;"></i> {{ $bahan->lain_lain }}</div>
                @endif
                @if($bahan->msds_file)
                <div style="margin-top:0.75rem;">
                    <a href="{{ route('msds.show', $bahan->id) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-primary" style="display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border-radius:6px;text-decoration:none;font-size:0.875rem;background:#0d6efd;color:#fff;border:none;">
                        📄 Lihat/Download MSDS
                    </a>
                </div>
                @endif
            </div>
            @endforeach

            @if($riskAssessment->kategoriHazardBahan)
            <div class="det-hazard-box">
                <span class="label"><i class="fas fa-exclamation-triangle" style="margin-right:6px;color:#d97706;"></i> Kategori Hazard Bahan</span>
                <span class="det-hazard-pill {{ $riskAssessment->kategoriHazardBahan->kategori }}">
                    {{ ucfirst(str_replace('_', ' ', $riskAssessment->kategoriHazardBahan->kategori)) }}
                </span>
            </div>
            @endif
        </div>
    </div>

    {{-- ============ PERALATAN OPERASI ============ --}}
    @if($riskAssessment->peralatanOperasi)
    <div class="det-section">
        <div class="det-section-head">
            <i class="fas fa-cogs"></i>
            <h2>Peralatan dan Kondisi Operasi</h2>
        </div>
        <div class="det-section-body">
            <ul class="det-check-list">
                <li class="det-check-item">
                    <span class="det-check-icon {{ $riskAssessment->peralatanOperasi->tekanan_tinggi ? 'yes' : 'no' }}">
                        <i class="fas fa-{{ $riskAssessment->peralatanOperasi->tekanan_tinggi ? 'check' : 'times' }}"></i>
                    </span>
                    Tekanan Tinggi
                </li>
                <li class="det-check-item">
                    <span class="det-check-icon {{ $riskAssessment->peralatanOperasi->suhu_tinggi ? 'yes' : 'no' }}">
                        <i class="fas fa-{{ $riskAssessment->peralatanOperasi->suhu_tinggi ? 'check' : 'times' }}"></i>
                    </span>
                    Suhu Tinggi
                </li>
                <li class="det-check-item">
                    <span class="det-check-icon {{ $riskAssessment->peralatanOperasi->nyala_api ? 'yes' : 'no' }}">
                        <i class="fas fa-{{ $riskAssessment->peralatanOperasi->nyala_api ? 'check' : 'times' }}"></i>
                    </span>
                    Nyala Api
                </li>
                <li class="det-check-item">
                    <span class="det-check-icon {{ $riskAssessment->peralatanOperasi->peralatan_berputar ? 'yes' : 'no' }}">
                        <i class="fas fa-{{ $riskAssessment->peralatanOperasi->peralatan_berputar ? 'check' : 'times' }}"></i>
                    </span>
                    Peralatan Berputar
                </li>
            </ul>

            @if($riskAssessment->peralatanOperasi->temperatur_maksimum || $riskAssessment->peralatanOperasi->tekanan_maksimum)
            <hr class="det-divider">
            <div class="det-grid">
                @if($riskAssessment->peralatanOperasi->temperatur_maksimum)
                <div class="det-field">
                    <label>Temperatur Maksimum</label>
                    <p>{{ $riskAssessment->peralatanOperasi->temperatur_maksimum }}°C</p>
                </div>
                @endif
                @if($riskAssessment->peralatanOperasi->tekanan_maksimum)
                <div class="det-field">
                    <label>Tekanan Maksimum</label>
                    <p>{{ $riskAssessment->peralatanOperasi->tekanan_maksimum }} atm</p>
                </div>
                @endif
            </div>
            @endif

            <div class="det-hazard-box">
                <span class="label"><i class="fas fa-exclamation-triangle" style="margin-right:6px;color:#d97706;"></i> Kategori Hazard Peralatan</span>
                <span class="det-hazard-pill {{ $riskAssessment->peralatanOperasi->kategori_hazard }}">
                    {{ ucfirst(str_replace('_', ' ', $riskAssessment->peralatanOperasi->kategori_hazard)) }}
                </span>
            </div>
        </div>
    </div>
    @endif

    {{-- ============ PELAKU KERJA ============ --}}
    @if($riskAssessment->pelakuKerja)
    <div class="det-section">
        <div class="det-section-head">
            <i class="fas fa-hard-hat"></i>
            <h2>Pelaku Kerja Laboratorium</h2>
        </div>
        <div class="det-section-body">
            <ul class="det-check-list">
                @php
                    $checks = [
                        'menyadari_faktor_manusia' => 'Menyadari faktor manusia dalam bekerja',
                        'memahami_bahaya_diri' => 'Memahami bahaya terhadap diri sendiri',
                        'memahami_bahaya_orang_lain' => 'Memahami bahaya terhadap orang lain',
                        'memahami_bahaya_lingkungan' => 'Memahami bahaya terhadap lingkungan',
                        'memahami_bahaya_peralatan' => 'Memahami bahaya terhadap peralatan',
                        'paham_tindakan_kecelakaan' => 'Paham tindakan saat terjadi kecelakaan',
                    ];
                @endphp
                @foreach($checks as $key => $label)
                <li class="det-check-item">
                    <span class="det-check-icon {{ $riskAssessment->pelakuKerja->$key ? 'yes' : 'no' }}">
                        <i class="fas fa-{{ $riskAssessment->pelakuKerja->$key ? 'check' : 'times' }}"></i>
                    </span>
                    {{ $label }}
                </li>
                @endforeach
            </ul>

            <div class="det-skill-box">
                <span class="label"><i class="fas fa-star" style="margin-right:6px;color:#d97706;"></i> Penilaian Keterampilan</span>
                <span class="det-skill-pill {{ $riskAssessment->pelakuKerja->penilaian_keterampilan }}">
                    {{ ucfirst(str_replace('_', ' ', $riskAssessment->pelakuKerja->penilaian_keterampilan)) }}
                </span>
            </div>
        </div>
    </div>
    @endif

    {{-- ============ CATATAN REVIEW ============ --}}
    @if($riskAssessment->catatan_dosen || $riskAssessment->catatan_safety_officer)
    <div class="det-section">
        <div class="det-section-head">
            <i class="fas fa-comment-alt"></i>
            <h2>Catatan Review</h2>
        </div>
        <div class="det-section-body">
            @if($riskAssessment->catatan_dosen)
            <div class="det-note blue">
                <div class="note-label"><i class="fas fa-chalkboard-teacher" style="margin-right:4px;"></i> Catatan Dosen Pembimbing</div>
                <p>{{ $riskAssessment->catatan_dosen }}</p>
            </div>
            @endif
            @if($riskAssessment->catatan_safety_officer)
            <div class="det-note amber">
                <div class="note-label"><i class="fas fa-shield-alt" style="margin-right:4px;"></i> Catatan Safety Officer</div>
                <p>{{ $riskAssessment->catatan_safety_officer }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- ============ ACTION FORM ============ --}}
    @if($riskAssessment->status === 'menunggu_safety_officer')
    <div class="det-action-card">
        <div class="det-action-head">
            <i class="fas fa-gavel"></i>
            <h2>Tindakan Safety Officer</h2>
        </div>
        <div class="det-action-body">

            {{-- Schedule Interview --}}
            @if($riskAssessment->jadwal_wawancara_dipilih_at)
                {{-- Student already picked a schedule --}}
                <div class="det-sched-confirmed">
                    <h3><i class="fas fa-calendar-check"></i> Mahasiswa Sudah Memilih Jadwal</h3>
                    <table class="det-sched-table">
                        <tr>
                            <td><i class="fas fa-calendar-day" style="margin-right:4px;"></i> Tanggal</td>
                            <td>{{ \Carbon\Carbon::parse($riskAssessment->jadwal_wawancara)->format('l, d F Y') }}</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-clock" style="margin-right:4px;"></i> Jam</td>
                            <td>{{ \Carbon\Carbon::parse($riskAssessment->jadwal_wawancara)->format('H:i') }} WIB</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-map-marker-alt" style="margin-right:4px;"></i> Lokasi</td>
                            <td>{{ $riskAssessment->tempat_wawancara }}</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-check-double" style="margin-right:4px;"></i> Dipilih pada</td>
                            <td>{{ \Carbon\Carbon::parse($riskAssessment->jadwal_wawancara_dipilih_at)->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>
                    <p class="det-sched-note">
                        <i class="fas fa-info-circle" style="margin-right:4px;"></i>
                        Mahasiswa telah memilih jadwal wawancara. Silakan lanjutkan proses approval/rejection.
                    </p>
                </div>
            @else
                {{-- Create schedule options --}}
                <div class="det-sched-box">
                    <h3><i class="fas fa-calendar-plus"></i> Jadwalkan Wawancara</h3>
                    <p class="det-sched-desc">Buat 2–5 pilihan jadwal wawancara. Mahasiswa akan memilih yang paling sesuai.</p>

                    <form action="{{ route('safety-officer.risk-assessment.store-schedule-options', $riskAssessment->id) }}" method="POST" id="schedule-options-form">
                        @csrf
                        @php
                            $scheduleOptions = old('schedule_options', $riskAssessment->jadwal_wawancara_options ?? []);
                            if (empty($scheduleOptions)) {
                                $scheduleOptions = [
                                    ['jadwal' => '', 'waktu' => '', 'tempat' => ''],
                                    ['jadwal' => '', 'waktu' => '', 'tempat' => '']
                                ];
                            }
                        @endphp

                        <div id="schedule-options-container">
                            @foreach($scheduleOptions as $index => $option)
                            <div class="det-opt-card" data-index="{{ $index }}">
                                <div class="det-opt-head">
                                    <span class="det-opt-label">Opsi {{ $index + 1 }}</span>
                                    @if($index > 1)
                                    <button type="button" class="det-opt-remove" onclick="removeScheduleOption(this)">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                    </button>
                                    @endif
                                </div>
                                <div class="det-form-row">
                                    <div class="det-form-group">
                                        <label>Tanggal Wawancara</label>
                                        <input type="date" name="schedule_options[{{ $index }}][jadwal]" required
                                               value="{{ old('schedule_options.' . $index . '.jadwal', $option['jadwal'] ?? '') }}"
                                               class="det-form-input">
                                    </div>
                                    <div class="det-form-group">
                                        <label>Jam Wawancara</label>
                                        <input type="time" name="schedule_options[{{ $index }}][waktu]" required
                                               value="{{ old('schedule_options.' . $index . '.waktu', $option['waktu'] ?? '') }}"
                                               class="det-form-input">
                                    </div>
                                </div>
                                <div class="det-form-group">
                                    <label>Tempat Wawancara</label>
                                    <input type="text" name="schedule_options[{{ $index }}][tempat]" required
                                           value="{{ old('schedule_options.' . $index . '.tempat', $option['tempat'] ?? '') }}"
                                           placeholder="Contoh: Ruang Lab, Office Room A, Online via Zoom"
                                           class="det-form-input">
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="det-btn-row">
                            <button type="button" class="det-btn green" onclick="addScheduleOption()">
                                <i class="fas fa-plus"></i> Tambah Opsi Jadwal
                            </button>
                            <button type="submit" class="det-btn blue">
                                <i class="fas fa-save"></i> Simpan Jadwal Wawancara
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <hr class="det-divider">

            {{-- Approval / Rejection Form --}}
            <form action="{{ route('safety-officer.risk-assessment.approve', $riskAssessment->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin dengan keputusan ini?')">
                @csrf

                <div class="det-form-group">
                    <label style="font-size:14px;font-weight:700;color:#111827;margin-bottom:10px;">Keputusan</label>
                    <div class="det-radio-group">
                        <label class="det-radio-item">
                            <input type="radio" name="persetujuan" value="setuju" id="approve" required>
                            <span class="det-radio-label">
                                <strong><i class="fas fa-check-circle" style="color:#059669;margin-right:4px;"></i> Setuju</strong>
                                — Lanjutkan ke Kepala Laboratorium
                            </span>
                        </label>
                        <label class="det-radio-item">
                            <input type="radio" name="persetujuan" value="tolak" id="reject" required>
                            <span class="det-radio-label">
                                <strong><i class="fas fa-times-circle" style="color:#dc2626;margin-right:4px;"></i> Tolak</strong>
                                — Risk Assessment ditolak
                            </span>
                        </label>
                    </div>
                </div>

                <div class="det-form-group">
                    <label>Catatan Review <span style="color:#dc2626;">*</span></label>
                    <textarea name="catatan" rows="4" required
                              placeholder="Berikan catatan mengenai review Anda, termasuk rekomendasi tindakan pencegahan..."
                              class="det-form-textarea"></textarea>
                    <div class="det-hint">Wajib diisi. Catatan akan diteruskan ke Kepala Lab dan mahasiswa.</div>
                </div>

                <div class="det-btn-row">
                    <button type="submit" class="det-btn primary">
                        <i class="fas fa-paper-plane"></i> Submit Review
                    </button>
                    <button type="button" class="det-btn amber" onclick="toggleRevision()">
                        <i class="fas fa-edit"></i> Minta Revisi
                    </button>
                </div>
            </form>

            {{-- Revision Form --}}
            <div class="det-revision-box" id="revisionForm">
                <h3><i class="fas fa-redo-alt"></i> Minta Revisi dari Mahasiswa</h3>
                <form action="{{ route('safety-officer.risk-assessment.request-revision', $riskAssessment->id) }}" method="POST">
                    @csrf
                    <div class="det-form-group">
                        <label>Catatan Revisi <span style="color:#dc2626;">*</span></label>
                        <textarea name="catatan_revisi" rows="4" required
                                  placeholder="Jelaskan poin-poin yang perlu direvisi oleh mahasiswa..."
                                  class="det-form-textarea"></textarea>
                    </div>
                    <div class="det-btn-row">
                        <button type="submit" class="det-btn amber">
                            <i class="fas fa-paper-plane"></i> Kirim Permintaan Revisi
                        </button>
                        <button type="button" class="det-btn outline" onclick="toggleRevision()">
                            Batal
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
function toggleRevision() {
    document.getElementById('revisionForm').classList.toggle('show');
}

let scheduleOptionCount = document.querySelectorAll('#schedule-options-container [data-index]').length;

function addScheduleOption() {
    if (scheduleOptionCount >= 5) { alert('Maksimal 5 jadwal wawancara.'); return; }
    const container = document.getElementById('schedule-options-container');
    const div = document.createElement('div');
    div.className = 'det-opt-card';
    div.dataset.index = scheduleOptionCount;
    div.innerHTML = `
        <div class="det-opt-head">
            <span class="det-opt-label">Opsi ${scheduleOptionCount + 1}</span>
            <button type="button" class="det-opt-remove" onclick="removeScheduleOption(this)"><i class="fas fa-trash-alt"></i> Hapus</button>
        </div>
        <div class="det-form-row">
            <div class="det-form-group">
                <label>Tanggal Wawancara</label>
                <input type="date" name="schedule_options[${scheduleOptionCount}][jadwal]" required class="det-form-input">
            </div>
            <div class="det-form-group">
                <label>Jam Wawancara</label>
                <input type="time" name="schedule_options[${scheduleOptionCount}][waktu]" required class="det-form-input">
            </div>
        </div>
        <div class="det-form-group">
            <label>Tempat Wawancara</label>
            <input type="text" name="schedule_options[${scheduleOptionCount}][tempat]" required placeholder="Contoh: Ruang Lab, Office Room A, Online via Zoom" class="det-form-input">
        </div>
    `;
    container.appendChild(div);
    scheduleOptionCount++;
    updateScheduleOptionNumbers();
    div.scrollIntoView({ behavior: 'smooth' });
}

function removeScheduleOption(btn) {
    const el = btn.closest('[data-index]');
    if (document.querySelectorAll('#schedule-options-container [data-index]').length <= 2) {
        alert('Minimal 2 jadwal wawancara harus ada.'); return;
    }
    el.remove(); scheduleOptionCount--;
    updateScheduleOptionNumbers();
}

function updateScheduleOptionNumbers() {
    document.querySelectorAll('#schedule-options-container [data-index]').forEach((opt, i) => {
        opt.dataset.index = i;
        const lbl = opt.querySelector('.det-opt-label');
        if (lbl) lbl.textContent = 'Opsi ' + (i + 1);
        const d = opt.querySelector('input[type="date"]');
        const t = opt.querySelector('input[type="time"]');
        const p = opt.querySelector('input[type="text"]');
        if (d) d.name = `schedule_options[${i}][jadwal]`;
        if (t) t.name = `schedule_options[${i}][waktu]`;
        if (p) p.name = `schedule_options[${i}][tempat]`;
    });
}

const sf = document.getElementById('schedule-options-form');
if (sf) {
    sf.addEventListener('submit', function(e) {
        const c = document.querySelectorAll('#schedule-options-container [data-index]').length;
        if (c < 2) { e.preventDefault(); alert('Minimal 2 jadwal wawancara harus dibuat.'); }
        if (c > 5) { e.preventDefault(); alert('Maksimal 5 jadwal wawancara.'); }
    });
}
</script>
@endpush