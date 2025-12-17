<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Risk Assessment</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <style>
        .form-section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }
        .form-section h3 {
            color: #333;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #667eea;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            color: #374151;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .form-group label.required::after {
            content: " *";
            color: #ef4444;
        }
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.95rem;
        }
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .checkbox-group {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 0.75rem;
        }
        .checkbox-item {
            display: flex;
            align-items: center;
        }
        .checkbox-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 0.5rem;
            cursor: pointer;
        }
        .radio-group {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 0.75rem;
        }
        .radio-item {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .radio-item:hover {
            border-color: #667eea;
            background-color: #f8f9ff;
        }
        .radio-item input[type="radio"] {
            width: 18px;
            height: 18px;
            margin-right: 0.75rem;
        }
        .btn-primary {
            background: #667eea;
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 6px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-primary:hover {
            background: #5568d3;
        }
        .btn-secondary {
            background: #6b7280;
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: background 0.2s;
        }
        .btn-secondary:hover {
            background: #4b5563;
        }
        .bahan-kimia-item {
            border: 2px solid #e5e7eb;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            position: relative;
        }
        .btn-remove {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: #ef4444;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
        }
        .btn-add {
            background: #10b981;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            margin-top: 1rem;
        }
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }
        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fbbf24;
        }
    </style>
</head>
<body class="h-full">

<div class="min-h-full">
    <x-mahasiswa.navbar :labs="[$lab]" :user="$riskAssessment->user"></x-mahasiswa.navbar>
    <x-mahasiswa.header>Edit Risk Assessment</x-mahasiswa.header>

    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            
            @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Terdapat kesalahan:</strong>
                <ul style="margin: 0.5rem 0 0 1.5rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if($riskAssessment->status !== 'draft')
            <div class="alert alert-warning">
                ⚠️ <strong>Perhatian:</strong> Risk Assessment ini sudah diajukan dan tidak dapat diedit. Anda hanya bisa melihat detailnya.
            </div>
            @endif

            <form action="{{ route('mahasiswa.risk-assessment.update', $riskAssessment->id) }}" method="POST" enctype="multipart/form-data" id="raForm">
                @csrf
                @method('PUT')

                <!-- SECTION 1: Data Mahasiswa -->
                <div class="form-section">
                    <h3>📋 Data Mahasiswa</h3>
                    
                    <div class="form-group">
                        <label class="required">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama', $riskAssessment->nama) }}" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="required">NIM</label>
                            <input type="text" name="nim" class="form-control" value="{{ old('nim', $riskAssessment->nim) }}" required>
                        </div>

                        <div class="form-group">
                            <label class="required">No. Kontak</label>
                            <input type="text" name="no_kontak" class="form-control" value="{{ old('no_kontak', $riskAssessment->no_kontak) }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="required">Alamat Kontak</label>
                        <textarea name="alamat_kontak" class="form-control" rows="2" required>{{ old('alamat_kontak', $riskAssessment->alamat_kontak) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="required">Jenis Risk Assessment</label>
                            <select name="jenis_ra" class="form-control" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="Penelitian" {{ old('jenis_ra', $riskAssessment->jenis_ra) == 'Penelitian' ? 'selected' : '' }}>Penelitian</option>
                                <option value="Praktikum" {{ old('jenis_ra', $riskAssessment->jenis_ra) == 'Praktikum' ? 'selected' : '' }}>Praktikum</option>
                                <option value="Lain-lain" {{ old('jenis_ra', $riskAssessment->jenis_ra) == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="required">Dosen Pembimbing</label>
                            <select name="dosen_pembimbing_id" class="form-control" required>
                                <option value="">-- Pilih Dosen --</option>
                                @foreach($dosens as $dosen)
                                <option value="{{ $dosen->id }}" {{ old('dosen_pembimbing_id', $riskAssessment->dosen_pembimbing_id) == $dosen->id ? 'selected' : '' }}>
                                    {{ $dosen->Nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="required">Topik / Judul Penelitian/Praktikum</label>
                        <input type="text" name="topik_judul" class="form-control" value="{{ old('topik_judul', $riskAssessment->topik_judul) }}" required>
                    </div>
                </div>

                <!-- SECTION 2: Bahan Kimia -->
                <div class="form-section">
                    <h3>⚗️ Identifikasi Bahan Kimia</h3>
                    
                    <div id="bahan-kimia-container">
                        @foreach($riskAssessment->bahanKimias as $index => $bahan)
                        <div class="bahan-kimia-item" data-index="{{ $index }}">
                            @if($index > 0)
                            <button type="button" class="btn-remove" onclick="removeBahanKimia(this)">✕ Hapus</button>
                            @endif
                            <h4 style="color: #667eea; margin-bottom: 1rem;">Bahan Kimia #{{ $index + 1 }}</h4>
                            
                            <input type="hidden" name="bahan_kimia[{{ $index }}][id]" value="{{ $bahan->id }}">
                            
                            <div class="form-group">
                                <label class="required">Nama Bahan Kimia</label>
                                <input type="text" name="bahan_kimia[{{ $index }}][nama_bahan]" class="form-control" value="{{ old('bahan_kimia.'.$index.'.nama_bahan', $bahan->nama_bahan) }}" required>
                            </div>

                            <div class="form-group">
                                <label class="required">Sifat Bahan (Pilih yang sesuai)</label>
                                <div class="checkbox-group">
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="bahan_kimia[{{ $index }}][sifat][]" value="explosive" id="exp_{{ $index }}" {{ $bahan->explosive ? 'checked' : '' }}>
                                        <label for="exp_{{ $index }}">☢️ Explosive</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="bahan_kimia[{{ $index }}][sifat][]" value="flammable" id="flam_{{ $index }}" {{ $bahan->flammable ? 'checked' : '' }}>
                                        <label for="flam_{{ $index }}">🔥 Flammable</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="bahan_kimia[{{ $index }}][sifat][]" value="toxic" id="tox_{{ $index }}" {{ $bahan->toxic ? 'checked' : '' }}>
                                        <label for="tox_{{ $index }}">☠️ Toxic</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="bahan_kimia[{{ $index }}][sifat][]" value="corrosive" id="cor_{{ $index }}" {{ $bahan->corrosive ? 'checked' : '' }}>
                                        <label for="cor_{{ $index }}">⚗️ Corrosive</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="bahan_kimia[{{ $index }}][sifat][]" value="irritant" id="irr_{{ $index }}" {{ $bahan->irritant ? 'checked' : '' }}>
                                        <label for="irr_{{ $index }}">⚠️ Irritant</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="bahan_kimia[{{ $index }}][sifat][]" value="oxidizing" id="oxi_{{ $index }}" {{ $bahan->oxidizing ? 'checked' : '' }}>
                                        <label for="oxi_{{ $index }}">💨 Oxidizing</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Lain-lain (jelaskan)</label>
                                <input type="text" name="bahan_kimia[{{ $index }}][lain_lain]" class="form-control" value="{{ old('bahan_kimia.'.$index.'.lain_lain', $bahan->lain_lain) }}" placeholder="Sebutkan sifat lain jika ada">
                            </div>

                            <div class="form-group">
                                <label>Upload MSDS Baru (PDF, max 5MB)</label>
                                @if($bahan->msds_file)
                                <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                    File saat ini: <a href="{{ Storage::url($bahan->msds_file) }}" target="_blank" style="color: #667eea;">Download</a>
                                </p>
                                @endif
                                <input type="file" name="bahan_kimia[{{ $index }}][msds_file]" class="form-control" accept=".pdf">
                                <p style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem;">Kosongkan jika tidak ingin mengganti file</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn-add" onclick="addBahanKimia()">+ Tambah Bahan Kimia</button>

                    <div class="form-group" style="margin-top: 2rem;">
                        <label class="required">Kategori Tingkat Hazard Bahan</label>
                        <div class="radio-group">
                            <label class="radio-item">
                                <input type="radio" name="kategori_hazard_bahan" value="sangat_hazardous" {{ old('kategori_hazard_bahan', $riskAssessment->kategoriHazardBahan->kategori ?? '') == 'sangat_hazardous' ? 'checked' : '' }} required>
                                <span>🔴 Sangat Hazardous</span>
                            </label>
                            <label class="radio-item">
                                <input type="radio" name="kategori_hazard_bahan" value="hazardous" {{ old('kategori_hazard_bahan', $riskAssessment->kategoriHazardBahan->kategori ?? '') == 'hazardous' ? 'checked' : '' }} required>
                                <span>🟠 Hazardous</span>
                            </label>
                            <label class="radio-item">
                                <input type="radio" name="kategori_hazard_bahan" value="moderat" {{ old('kategori_hazard_bahan', $riskAssessment->kategoriHazardBahan->kategori ?? '') == 'moderat' ? 'checked' : '' }} required>
                                <span>🟡 Moderat</span>
                            </label>
                            <label class="radio-item">
                                <input type="radio" name="kategori_hazard_bahan" value="tidak_hazardous" {{ old('kategori_hazard_bahan', $riskAssessment->kategoriHazardBahan->kategori ?? '') == 'tidak_hazardous' ? 'checked' : '' }} required>
                                <span>🟢 Tidak Hazardous</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: Peralatan & Kondisi Operasi -->
                <div class="form-section">
                    <h3>🔧 Peralatan & Kondisi Operasi</h3>
                    
                    <div class="form-group">
                        <label>Kondisi Percobaan (Centang yang sesuai)</label>
                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <input type="checkbox" name="peralatan[tekanan_tinggi]" value="1" id="tek_tinggi" {{ $riskAssessment->peralatanOperasi->tekanan_tinggi ? 'checked' : '' }}>
                                <label for="tek_tinggi">Menggunakan Tekanan Tinggi</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="peralatan[suhu_tinggi]" value="1" id="suhu_tinggi" {{ $riskAssessment->peralatanOperasi->suhu_tinggi ? 'checked' : '' }}>
                                <label for="suhu_tinggi">Menggunakan Suhu Tinggi</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="peralatan[nyala_api]" value="1" id="nyala_api" {{ $riskAssessment->peralatanOperasi->nyala_api ? 'checked' : '' }}>
                                <label for="nyala_api">Menggunakan Nyala Api</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="peralatan[peralatan_berputar]" value="1" id="per_putar" {{ $riskAssessment->peralatanOperasi->peralatan_berputar ? 'checked' : '' }}>
                                <label for="per_putar">Menggunakan Peralatan Berputar</label>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label>Temperatur Maksimum (°C)</label>
                            <input type="number" step="0.01" name="peralatan[temperatur_maksimum]" class="form-control" value="{{ old('peralatan.temperatur_maksimum', $riskAssessment->peralatanOperasi->temperatur_maksimum) }}" placeholder="Contoh: 80">
                        </div>

                        <div class="form-group">
                            <label>Tekanan Maksimum (atm)</label>
                            <input type="number" step="0.01" name="peralatan[tekanan_maksimum]" class="form-control" value="{{ old('peralatan.tekanan_maksimum', $riskAssessment->peralatanOperasi->tekanan_maksimum) }}" placeholder="Contoh: 1">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="required">Kategori Hazard Peralatan</label>
                        <div class="radio-group">
                            <label class="radio-item">
                                <input type="radio" name="peralatan[kategori_hazard]" value="sangat_hazardous" {{ old('peralatan.kategori_hazard', $riskAssessment->peralatanOperasi->kategori_hazard) == 'sangat_hazardous' ? 'checked' : '' }} required>
                                <span>🔴 Sangat Hazardous</span>
                            </label>
                            <label class="radio-item">
                                <input type="radio" name="peralatan[kategori_hazard]" value="hazardous" {{ old('peralatan.kategori_hazard', $riskAssessment->peralatanOperasi->kategori_hazard) == 'hazardous' ? 'checked' : '' }} required>
                                <span>🟠 Hazardous</span>
                            </label>
                            <label class="radio-item">
                                <input type="radio" name="peralatan[kategori_hazard]" value="moderat" {{ old('peralatan.kategori_hazard', $riskAssessment->peralatanOperasi->kategori_hazard) == 'moderat' ? 'checked' : '' }} required>
                                <span>🟡 Moderat</span>
                            </label>
                            <label class="radio-item">
                                <input type="radio" name="peralatan[kategori_hazard]" value="tidak_hazardous" {{ old('peralatan.kategori_hazard', $riskAssessment->peralatanOperasi->kategori_hazard) == 'tidak_hazardous' ? 'checked' : '' }} required>
                                <span>🟢 Tidak Hazardous</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: Pelaku Kerja -->
                <div class="form-section">
                    <h3>👤 Pelaku Kerja Laboratorium</h3>
                    
                    <div class="form-group">
                        <label class="required">Pemahaman Keselamatan (Centang semua yang benar)</label>
                        <div class="checkbox-group" style="grid-template-columns: 1fr;">
                            <div class="checkbox-item">
                                <input type="checkbox" name="pelaku_kerja[menyadari_faktor_manusia]" value="1" id="pk1" {{ $riskAssessment->pelakuKerja->menyadari_faktor_manusia ? 'checked' : '' }} required>
                                <label for="pk1">Saya menyadari bahwa faktor manusia mempunyai andil yang besar terhadap terjadinya suatu kecelakaan kerja</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="pelaku_kerja[memahami_bahaya_diri]" value="1" id="pk2" {{ $riskAssessment->pelakuKerja->memahami_bahaya_diri ? 'checked' : '' }} required>
                                <label for="pk2">Saya memahami bahaya yang ditimbulkan dari bahan yang saya pergunakan terhadap diri saya sendiri</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="pelaku_kerja[memahami_bahaya_orang_lain]" value="1" id="pk3" {{ $riskAssessment->pelakuKerja->memahami_bahaya_orang_lain ? 'checked' : '' }} required>
                                <label for="pk3">Saya memaklumi bahaya yang dapat ditimbulkan dari bahan yang saya pergunakan terhadap orang lain</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="pelaku_kerja[memahami_bahaya_lingkungan]" value="1" id="pk4" {{ $riskAssessment->pelakuKerja->memahami_bahaya_lingkungan ? 'checked' : '' }} required>
                                <label for="pk4">Saya memahami bahaya yang ditimbulkan dari bahan yang saya pergunakan terhadap lingkungan</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="pelaku_kerja[memahami_bahaya_peralatan]" value="1" id="pk5" {{ $riskAssessment->pelakuKerja->memahami_bahaya_peralatan ? 'checked' : '' }} required>
                                <label for="pk5">Saya memahami bahaya apa saja yang dapat ditimbulkan dari peralatan yang saya pergunakan</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="pelaku_kerja[paham_tindakan_kecelakaan]" value="1" id="pk6" {{ $riskAssessment->pelakuKerja->paham_tindakan_kecelakaan ? 'checked' : '' }} required>
                                <label for="pk6">Saya paham tindakan apa yang harus dilakukan jika terjadi kecelakaan yang disebabkan oleh percobaan yang saya lakukan</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="required">Penilaian Keterampilan Diri</label>
                        <div class="radio-group">
                            <label class="radio-item">
                                <input type="radio" name="pelaku_kerja[penilaian_keterampilan]" value="ceroboh" {{ old('pelaku_kerja.penilaian_keterampilan', $riskAssessment->pelakuKerja->penilaian_keterampilan) == 'ceroboh' ? 'checked' : '' }} required>
                                <span>Ceroboh</span>
                            </label>
                            <label class="radio-item">
                                <input type="radio" name="pelaku_kerja[penilaian_keterampilan]" value="kurang_terampil" {{ old('pelaku_kerja.penilaian_keterampilan', $riskAssessment->pelakuKerja->penilaian_keterampilan) == 'kurang_terampil' ? 'checked' : '' }} required>
                                <span>Kurang Terampil</span>
                            </label>
                            <label class="radio-item">
                                <input type="radio" name="pelaku_kerja[penilaian_keterampilan]" value="cukup_terampil" {{ old('pelaku_kerja.penilaian_keterampilan', $riskAssessment->pelakuKerja->penilaian_keterampilan) == 'cukup_terampil' ? 'checked' : '' }} required>
                                <span>Cukup Terampil</span>
                            </label>
                            <label class="radio-item">
                                <input type="radio" name="pelaku_kerja[penilaian_keterampilan]" value="sangat_terampil" {{ old('pelaku_kerja.penilaian_keterampilan', $riskAssessment->pelakuKerja->penilaian_keterampilan) == 'sangat_terampil' ? 'checked' : '' }} required>
                                <span>Sangat Terampil</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- SECTION 5: Pernyataan -->
                <div class="form-section">
                    <h3>✍️ Pernyataan Mahasiswa</h3>
                    
                    <div class="form-group">
                        <div class="checkbox-item" style="background: #f8f9ff; padding: 1.5rem; border-radius: 6px; border: 2px solid #667eea;">
                            <input type="checkbox" name="setuju_bertanggung_jawab" value="1" id="pernyataan" {{ $riskAssessment->pernyataanMahasiswa->setuju_bertanggung_jawab ? 'checked' : '' }} required>
                            <label for="pernyataan" style="line-height: 1.6;">
                                <strong>Saya menyatakan bahwa:</strong><br>
                                Saya memahami tentang apa yang akan saya lakukan dalam percobaan tersebut dan bertanggung jawab terhadap keselamatan jalannya percobaan serta tidak melakukan kegiatan laboratorium di luar pekerjaan yang diijinkan.
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Tanda Tangan Digital (Opsional - Kosongkan jika tidak ingin mengganti)</label>
                        @if($riskAssessment->pernyataanMahasiswa->tanda_tangan)
                        <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Tanda tangan saat ini:</p>
                        <div style="border: 2px solid #e5e7eb; border-radius: 6px; padding: 1rem; margin-bottom: 1rem; max-width: 400px;">
                            <img src="{{ $riskAssessment->pernyataanMahasiswa->tanda_tangan }}" alt="Tanda Tangan Lama" style="max-width: 100%; height: auto;">
                        </div>
                        @endif
                        <input type="hidden" name="tanda_tangan" id="signature_data">
                        <canvas id="signature-pad" width="600" height="200" style="border: 2px solid #d1d5db; border-radius: 6px; cursor: crosshair;"></canvas>
                        <div style="margin-top: 0.5rem;">
                            <button type="button" onclick="clearSignature()" style="padding: 0.5rem 1rem; background: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer;">Hapus</button>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn-primary">💾 Update Risk Assessment</button>
                    <a href="{{ route('mahasiswa.risk-assessment.show', $riskAssessment->id) }}" class="btn-secondary">❌ Batal</a>
                </div>

            </form>

        </div>
    </main>
</div>

<script>
// Counter untuk bahan kimia - mulai dari jumlah existing
let bahanKimiaIndex = {{ $riskAssessment->bahanKimias->count() }};

function addBahanKimia() {
    const container = document.getElementById('bahan-kimia-container');
    const newItem = `
        <div class="bahan-kimia-item" data-index="${bahanKimiaIndex}">
            <button type="button" class="btn-remove" onclick="removeBahanKimia(this)">✕ Hapus</button>
            <h4 style="color: #667eea; margin-bottom: 1rem;">Bahan Kimia #${bahanKimiaIndex + 1}</h4>
            
            <div class="form-group">
                <label class="required">Nama Bahan Kimia</label>
                <input type="text" name="bahan_kimia[${bahanKimiaIndex}][nama_bahan]" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="required">Sifat Bahan</label>
                <div class="checkbox-group">
                    <div class="checkbox-item">
                        <input type="checkbox" name="bahan_kimia[${bahanKimiaIndex}][sifat][]" value="explosive" id="exp_${bahanKimiaIndex}">
                        <label for="exp_${bahanKimiaIndex}">☢️ Explosive</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" name="bahan_kimia[${bahanKimiaIndex}][sifat][]" value="flammable" id="flam_${bahanKimiaIndex}">
                        <label for="flam_${bahanKimiaIndex}">🔥 Flammable</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" name="bahan_kimia[${bahanKimiaIndex}][sifat][]" value="toxic" id="tox_${bahanKimiaIndex}">
                        <label for="tox_${bahanKimiaIndex}">☠️ Toxic</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" name="bahan_kimia[${bahanKimiaIndex}][sifat][]" value="corrosive" id="cor_${bahanKimiaIndex}">
                        <label for="cor_${bahanKimiaIndex}">⚗️ Corrosive</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" name="bahan_kimia[${bahanKimiaIndex}][sifat][]" value="irritant" id="irr_${bahanKimiaIndex}">
                        <label for="irr_${bahanKimiaIndex}">⚠️ Irritant</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" name="bahan_kimia[${bahanKimiaIndex}][sifat][]" value="oxidizing" id="oxi_${bahanKimiaIndex}">
                        <label for="oxi_${bahanKimiaIndex}">💨 Oxidizing</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Lain-lain (jelaskan)</label>
                <input type="text" name="bahan_kimia[${bahanKimiaIndex}][lain_lain]" class="form-control">
            </div>

            <div class="form-group">
                <label>Upload MSDS (PDF)</label>
                <input type="file" name="bahan_kimia[${bahanKimiaIndex}][msds_file]" class="form-control" accept=".pdf">
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', newItem);
    bahanKimiaIndex++;
}

function removeBahanKimia(button) {
    if (confirm('Hapus bahan kimia ini?')) {
        button.closest('.bahan-kimia-item').remove();
    }
}

// Signature Pad
const canvas = document.getElementById('signature-pad');
const ctx = canvas.getContext('2d');
let isDrawing = false;
let lastX = 0;
let lastY = 0;

canvas.addEventListener('mousedown', startDrawing);
canvas.addEventListener('mousemove', draw);
canvas.addEventListener('mouseup', stopDrawing);
canvas.addEventListener('mouseout', stopDrawing);

// Touch events for mobile
canvas.addEventListener('touchstart', handleTouchStart);
canvas.addEventListener('touchmove', handleTouchMove);
canvas.addEventListener('touchend', stopDrawing);

function startDrawing(e) {
    isDrawing = true;
    [lastX, lastY] = [e.offsetX, e.offsetY];
}

function draw(e) {
    if (!isDrawing) return;
    ctx.beginPath();
    ctx.moveTo(lastX, lastY);
    ctx.lineTo(e.offsetX, e.offsetY);
    ctx.strokeStyle = '#000';
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.stroke();
    [lastX, lastY] = [e.offsetX, e.offsetY];
}

function stopDrawing() {
    if (isDrawing) {
        isDrawing = false;
        document.getElementById('signature_data').value = canvas.toDataURL();
    }
}

function handleTouchStart(e) {
    e.preventDefault();
    const touch = e.touches[0];
    const rect = canvas.getBoundingClientRect();
    isDrawing = true;
    lastX = touch.clientX - rect.left;
    lastY = touch.clientY - rect.top;
}

function handleTouchMove(e) {
    if (!isDrawing) return;
    e.preventDefault();
    const touch = e.touches[0];
    const rect = canvas.getBoundingClientRect();
    const x = touch.clientX - rect.left;
    const y = touch.clientY - rect.top;
    
    ctx.beginPath();
    ctx.moveTo(lastX, lastY);
    ctx.lineTo(x, y);
    ctx.strokeStyle = '#000';
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.stroke();
    lastX = x;
    lastY = y;
}

function clearSignature() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    document.getElementById('signature_data').value = '';
}

// Checkbox validation
document.getElementById('raForm').addEventListener('submit', function(e) {
    const checkboxes = document.querySelectorAll('input[name^="pelaku_kerja"]:not([type="radio"])');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    if (!allChecked) {
        e.preventDefault();
        alert('Mohon centang semua pemahaman keselamatan pada bagian Pelaku Kerja Laboratorium');
        return false;
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
</body>
</html>