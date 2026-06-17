<?php

namespace App\Services;

use App\Models\RiskAssessment;
use Carbon\Carbon;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class RiskAssessmentDocumentService
{
    /**
     * Generate Risk Assessment sebagai PDF
     */
    public function generatePdf(RiskAssessment $riskAssessment)
    {
        try {
            $phpWord = $this->generateDocument($riskAssessment);

            // Generate sebagai DOCX dulu, nanti bisa convert ke PDF dengan library lain
            // Untuk sekarang, generate langsung sebagai Word kemudian user bisa convert
            $filename = 'RA-'.$riskAssessment->id_ra.'-'.date('YmdHis').'.docx';
            $filePath = storage_path('app/public/'.$filename);

            // Create directory if not exists
            if (! is_dir(storage_path('app/public'))) {
                mkdir(storage_path('app/public'), 0755, true);
            }

            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save($filePath);

            return response()->download($filePath, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ])->deleteFileAfterSend();
        } catch (\Exception $e) {
            \Log::error('Error generating PDF: '.$e->getMessage());

            return response()->json(['error' => 'Gagal menghasilkan PDF: '.$e->getMessage()], 500);
        }
    }

    /**
     * Generate Risk Assessment sebagai Word Document
     */
    public function generateWord(RiskAssessment $riskAssessment)
    {
        try {
            $phpWord = $this->generateDocument($riskAssessment);

            // Generate sebagai DOCX
            $filename = 'RA-'.$riskAssessment->id_ra.'-'.date('YmdHis').'.docx';
            $filePath = storage_path('app/public/'.$filename);

            // Create directory if not exists
            if (! is_dir(storage_path('app/public'))) {
                mkdir(storage_path('app/public'), 0755, true);
            }

            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save($filePath);

            return response()->download($filePath, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ])->deleteFileAfterSend();
        } catch (\Exception $e) {
            \Log::error('Error generating Word: '.$e->getMessage());

            return response()->json(['error' => 'Gagal menghasilkan Word: '.$e->getMessage()], 500);
        }
    }

    /**
     * Generate Document (Template untuk PDF dan Word)
     */
    private function generateDocument(RiskAssessment $riskAssessment): PhpWord
    {
        $phpWord = new PhpWord;

        // Set default font
        $phpWord->setDefaultFontName('Calibri');
        $phpWord->setDefaultFontSize(11);

        // Add section (1 inch = 1440 twips)
        $section = $phpWord->addSection();
        $section->setMarginTop(1440);
        $section->setMarginBottom(1440);
        $section->setMarginLeft(1440);
        $section->setMarginRight(1440);

        // Header
        $this->addHeader($phpWord, $section, $riskAssessment);

        // Identity Section
        $this->addIdentitySection($section, $riskAssessment);

        // Chemical Section
        $this->addChemicalSection($section, $riskAssessment);

        // Equipment Section
        $this->addEquipmentSection($section, $riskAssessment);

        // Worker Section
        $this->addWorkerSection($section, $riskAssessment);

        // Approval Section
        $this->addApprovalSection($section, $riskAssessment);

        // Deadline Section
        if ($riskAssessment->batas_waktu_peminjaman) {
            $this->addDeadlineSection($section, $riskAssessment);
        }

        return $phpWord;
    }

    private function addHeader($phpWord, $section, RiskAssessment $riskAssessment)
    {
        $table = $section->addTable();
        $table->setWidth(100 * 50);

        $row = $table->addRow();
        $row->addCell(4000)->addText('RISK ASSESSMENT FORM', ['size' => 14, 'bold' => true]);
        $row->addCell(2000)->addText($riskAssessment->id_ra ?? 'N/A', ['size' => 10, 'bold' => true, 'align' => 'right']);

        $row = $table->addRow();
        $row->addCell(4000)->addText('Sistem Manajemen Laboratorium Teknik Kimia', ['size' => 10, 'italic' => true]);
        $row->addCell(2000)->addText('Tanggal: '.now()->format('d/m/Y'), ['size' => 9, 'align' => 'right']);

        $section->addText('');
    }

    private function addIdentitySection($section, RiskAssessment $riskAssessment)
    {
        $section->addTitle('I. DATA MAHASISWA/PENELITI', 1);

        $table = $section->addTable();
        $table->setWidth(100 * 50);

        $data = [
            ['Nama', $riskAssessment->nama],
            ['NIM/NPP', $riskAssessment->nim],
            ['No. Kontak', $riskAssessment->no_kontak],
            ['Alamat Kontak', $riskAssessment->alamat_kontak],
            ['Jenis RA', $riskAssessment->jenis_ra],
            ['Topik/Judul', $riskAssessment->topik_judul],
            ['Dosen Pembimbing', $riskAssessment->dosen_pembimbing_nama],
            ['Laboratorium', $riskAssessment->daftarLab?->Nama_Laboratorium ?? 'N/A'],
        ];

        foreach ($data as $item) {
            $row = $table->addRow();
            $row->addCell(2000)->addText($item[0].':', ['bold' => true]);
            $row->addCell(4000)->addText($item[1]);
        }

        $section->addText('');
    }

    private function addChemicalSection($section, RiskAssessment $riskAssessment)
    {
        $section->addTitle('II. BAHAN KIMIA', 1);

        if ($riskAssessment->bahanKimias->count() > 0) {
            $table = $section->addTable();
            $table->setWidth(100 * 50);

            // Header
            $row = $table->addRow();
            $row->addCell(2000)->addText('No.', ['bold' => true, 'bgColor' => 'FFFFCC']);
            $row->addCell(2000)->addText('Nama Bahan', ['bold' => true, 'bgColor' => 'FFFFCC']);
            $row->addCell(2000)->addText('Sifat', ['bold' => true, 'bgColor' => 'FFFFCC']);

            // Data
            foreach ($riskAssessment->bahanKimias as $index => $bahan) {
                $row = $table->addRow();
                $row->addCell(2000)->addText($index + 1);
                $row->addCell(2000)->addText($bahan->nama_bahan);
                $row->addCell(2000)->addText(implode(', ', $bahan->sifat ?? []));
            }
        } else {
            $section->addText('Tidak ada bahan kimia yang digunakan.');
        }

        $section->addText('Kategori Hazard: '.($riskAssessment->kategoriHazardBahan?->kategori ?? 'N/A'));
        $section->addText('');
    }

    private function addEquipmentSection($section, RiskAssessment $riskAssessment)
    {
        $section->addTitle('III. PERALATAN & KONDISI OPERASI', 1);

        if ($riskAssessment->peralatanOperasi) {
            $peralatan = $riskAssessment->peralatanOperasi;

            $data = [
                ['Tekanan Tinggi', $peralatan->tekanan_tinggi ? 'Ya' : 'Tidak'],
                ['Suhu Tinggi', $peralatan->suhu_tinggi ? 'Ya' : 'Tidak'],
                ['Nyala Api', $peralatan->nyala_api ? 'Ya' : 'Tidak'],
                ['Peralatan Berputar', $peralatan->peralatan_berputar ? 'Ya' : 'Tidak'],
                ['Temperatur Maksimum', $peralatan->temperatur_maksimum ? $peralatan->temperatur_maksimum.'°C' : 'N/A'],
                ['Tekanan Maksimum', $peralatan->tekanan_maksimum ? $peralatan->tekanan_maksimum.' bar' : 'N/A'],
            ];

            $table = $section->addTable();
            $table->setWidth(100 * 50);

            foreach ($data as $item) {
                $row = $table->addRow();
                $row->addCell(2000)->addText($item[0].':', ['bold' => true]);
                $row->addCell(4000)->addText($item[1]);
            }
        }

        $section->addText('');
    }

    private function addWorkerSection($section, RiskAssessment $riskAssessment)
    {
        $section->addTitle('IV. PENILAIAN PELAKU KERJA', 1);

        if ($riskAssessment->pelakuKerja) {
            $pelaku = $riskAssessment->pelakuKerja;

            $checks = [
                'Menyadari Faktor Manusia' => $pelaku->menyadari_faktor_manusia,
                'Memahami Bahaya Diri' => $pelaku->memahami_bahaya_diri,
                'Memahami Bahaya Orang Lain' => $pelaku->memahami_bahaya_orang_lain,
                'Memahami Bahaya Lingkungan' => $pelaku->memahami_bahaya_lingkungan,
                'Memahami Bahaya Peralatan' => $pelaku->memahami_bahaya_peralatan,
            ];

            foreach ($checks as $label => $value) {
                $section->addText('☑ '.$label.': '.($value ? 'Ya' : 'Tidak'));
            }

            $section->addText('Penilaian Keterampilan: '.ucfirst($pelaku->penilaian_keterampilan));
        }

        $section->addText('');
    }

    private function addApprovalSection($section, RiskAssessment $riskAssessment)
    {
        $section->addTitle('V. STATUS PERSETUJUAN', 1);

        $approvals = [
            'Dosen Pembimbing' => [
                'nama' => $riskAssessment->dosen_pembimbing_nama,
                'status' => $riskAssessment->persetujuan_dosen,
                'tanggal' => $riskAssessment->tanggal_persetujuan_dosen,
                'catatan' => $riskAssessment->catatan_dosen,
            ],
            'Safety Officer' => [
                'nama' => $riskAssessment->safety_officer_nama,
                'status' => $riskAssessment->persetujuan_safety_officer,
                'tanggal' => $riskAssessment->tanggal_persetujuan_safety_officer,
                'catatan' => $riskAssessment->catatan_safety_officer,
            ],
            'Kepala Lab' => [
                'nama' => $riskAssessment->kepala_lab_nama,
                'status' => $riskAssessment->persetujuan_kepala_lab,
                'tanggal' => $riskAssessment->tanggal_persetujuan_kepala_lab,
                'catatan' => $riskAssessment->catatan_kepala_lab,
            ],
            'Kaprodi' => [
                'nama' => $riskAssessment->kaprodi_nama,
                'status' => $riskAssessment->persetujuan_kaprodi,
                'tanggal' => $riskAssessment->tanggal_persetujuan_kaprodi,
                'catatan' => $riskAssessment->catatan_kaprodi,
            ],
        ];

        foreach ($approvals as $label => $approval) {
            $status = $approval['status'] === null ? 'Menunggu' : ($approval['status'] ? '✓ Disetujui' : '✗ Ditolak');
            $statusColor = $approval['status'] === true ? '00AA00' : ($approval['status'] === false ? 'AA0000' : '0000AA');

            $section->addText($label.': '.$status, ['color' => $statusColor, 'bold' => true]);
            if ($approval['nama']) {
                $section->addText('  Nama: '.$approval['nama'], ['indent' => 360]); // 0.25 inch = 360 twips
            }
            if ($approval['tanggal']) {
                $section->addText('  Tanggal: '.Carbon::parse($approval['tanggal'])->format('d/m/Y H:i'), ['indent' => 360]);
            }
            if ($approval['catatan']) {
                $section->addText('  Catatan: '.$approval['catatan'], ['indent' => 360]);
            }
        }

        $section->addText('');
    }

    private function addDeadlineSection($section, RiskAssessment $riskAssessment)
    {
        $section->addTitle('VI. BATAS WAKTU PEMINJAMAN', 1);

        $table = $section->addTable();
        $table->setWidth(100 * 50);

        $data = [
            ['Batas Waktu Peminjaman', Carbon::parse($riskAssessment->batas_waktu_peminjaman)->format('d/m/Y H:i')],
            ['Durasi (Bulan)', $riskAssessment->durasi_batas_peminjaman ?? 'N/A'],
        ];

        if ($riskAssessment->pengajuan_perpanjangan) {
            $data[] = ['Status Perpanjangan', $riskAssessment->persetujuan_perpanjangan_kaprodi ? 'Disetujui' : 'Menunggu'];
            $data[] = ['Durasi Perpanjangan', $riskAssessment->durasi_perpanjangan_disetujui ?? 'N/A'];
        }

        foreach ($data as $item) {
            $row = $table->addRow();
            $row->addCell(2000)->addText($item[0].':', ['bold' => true]);
            $row->addCell(4000)->addText($item[1]);
        }

        $section->addText('');
    }
}
