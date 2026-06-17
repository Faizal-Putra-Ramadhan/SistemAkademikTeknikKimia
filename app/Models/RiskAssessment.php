<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RiskAssessment extends Model
{
    protected $table = 'risk_assessments';

    protected $fillable = [
        'user_id',
        'id_ra',
        'nama',
        'nim',
        'no_kontak',
        'alamat_kontak',
        'daftar_lab_id',
        'jenis_ra',
        'topik_judul',
        'dosen_pembimbing_id',
        'dosen_pembimbing_nama',
        'nomor_identitas_dosen',
        'status',
        'kategori_resiko_dosen',
        'persetujuan_dosen',
        'catatan_dosen',
        'tanggal_persetujuan_dosen',
        'safety_officer_id',
        'safety_officer_nama',
        'nomor_identitas_safety_officer',
        'jadwal_wawancara',
        'tempat_wawancara',
        'jadwal_wawancara_options',
        'jadwal_wawancara_dipilih_at',
        'persetujuan_safety_officer',
        'catatan_safety_officer',
        'tanggal_persetujuan_safety_officer',
        'kepala_lab_id',
        'kepala_lab_nama',
        'nomor_identitas_kepala_lab',
        'persetujuan_kepala_lab',
        'catatan_kepala_lab',
        'tanggal_persetujuan_kepala_lab',
        'kaprodi_id',
        'kaprodi_nama',
        'nomor_identitas_kaprodi',
        'persetujuan_kaprodi',
        'catatan_kaprodi',
        'tanggal_persetujuan_kaprodi',
        'batas_waktu_peminjaman',
        'durasi_batas_peminjaman',
        'pengajuan_perpanjangan',
        'alasan_perpanjangan',
        'tanggal_pengajuan_perpanjangan',
        'durasi_perpanjangan_diminta',
        'persetujuan_perpanjangan_kaprodi',
        'catatan_perpanjangan_kaprodi',
        'tanggal_persetujuan_perpanjangan',
        'durasi_perpanjangan_disetujui',
        'jumlah_perpanjangan',
        'batas_waktu_peminjaman_sebelumnya',
        'notifikasi_deadline_terkirim',
        'tanggal_notifikasi_deadline',
    ];

    protected $casts = [
        'persetujuan_dosen' => 'boolean',
        'persetujuan_safety_officer' => 'boolean',
        'persetujuan_kepala_lab' => 'boolean',
        'persetujuan_kaprodi' => 'boolean',
        'jadwal_wawancara' => 'datetime',
        'jadwal_wawancara_options' => 'json',
        'jadwal_wawancara_dipilih_at' => 'datetime',
        'tanggal_persetujuan_dosen' => 'datetime',
        'tanggal_persetujuan_safety_officer' => 'datetime',
        'tanggal_persetujuan_kepala_lab' => 'datetime',
        'tanggal_persetujuan_kaprodi' => 'datetime',
        'batas_waktu_peminjaman' => 'datetime',
        'pengajuan_perpanjangan' => 'boolean',
        'tanggal_pengajuan_perpanjangan' => 'datetime',
        'persetujuan_perpanjangan_kaprodi' => 'boolean',
        'tanggal_persetujuan_perpanjangan' => 'datetime',
        'batas_waktu_peminjaman_sebelumnya' => 'datetime',
        'notifikasi_deadline_terkirim' => 'boolean',
        'tanggal_notifikasi_deadline' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(DaftarUser::class, 'user_id');
    }

    public function daftarLab(): BelongsTo
    {
        return $this->belongsTo(DaftarLab::class, 'daftar_lab_id');
    }

    public function dosenPembimbing(): BelongsTo
    {
        return $this->belongsTo(DaftarUser::class, 'dosen_pembimbing_id');
    }

    public function safetyOfficer(): BelongsTo
    {
        return $this->belongsTo(DaftarUser::class, 'safety_officer_id');
    }

    public function kepalaLab(): BelongsTo
    {
        return $this->belongsTo(DaftarUser::class, 'kepala_lab_id');
    }

    public function kaprodi()
    {
        return $this->belongsTo(DaftarUser::class, 'kaprodi_id');
    }

    public function bebasLabRequest(): HasOne
    {
        return $this->hasOne(BebasLabRequest::class, 'risk_assessment_id');
    }

    public function bahanKimias(): HasMany
    {
        return $this->hasMany(RaBahanKimia::class, 'risk_assessment_id');
    }

    // Alias untuk compatibility
    public function raBahanKimias(): HasMany
    {
        return $this->hasMany(RaBahanKimia::class, 'risk_assessment_id');
    }

    public function kategoriHazardBahan(): HasOne
    {
        return $this->hasOne(RaKategoriHazardBahan::class, 'risk_assessment_id');
    }

    public function peralatanOperasi(): HasOne
    {
        return $this->hasOne(RaPeralatanOperasi::class, 'risk_assessment_id');
    }

    public function pelakuKerja(): HasOne
    {
        return $this->hasOne(RaPelakuKerja::class, 'risk_assessment_id');
    }

    public function pernyataanMahasiswa(): HasOne
    {
        return $this->hasOne(RaPernyataanMahasiswa::class, 'risk_assessment_id');
    }

    public function peminjamanAlats(): HasMany
    {
        return $this->hasMany(PeminjamanAlat::class, 'risk_assessment_id');
    }

    // Helper Methods

    /**
     * Generate ID Risk Assessment dengan format RA-YYXXNNN
     * YY = 2 digit tahun
     * XX = nomor urut risk assessment (2 digit)
     * NNN = 3 digit terakhir NIM
     */
    public function generateIdRa()
    {
        if ($this->id_ra) {
            return $this->id_ra; // Jika sudah ada, return yang lama
        }

        $year = date('y'); // 2 digit tahun terakhir

        // Hitung jumlah risk assessment yang sudah disetujui untuk user ini di tahun ini
        $count = self::where('user_id', $this->user_id)
            ->where('status', 'disetujui')
            ->whereYear('created_at', date('Y'))
            ->count();

        $urutan = str_pad($count + 1, 2, '0', STR_PAD_LEFT); // 2 digit urutan

        // 3 digit terakhir NIM
        $nim3digit = substr($this->nim, -3);

        $idRa = "RA-{$year}{$urutan}{$nim3digit}";

        $this->id_ra = $idRa;
        $this->save();

        return $idRa;
    }

    public function updateBatasWaktuPeminjaman()
    {
        $startDate = $this->tanggal_persetujuan_kepala_lab ?? now();
        $durasi = $this->durasi_batas_peminjaman ?? 4;

        $this->batas_waktu_peminjaman = Carbon::parse($startDate)->addMonths($durasi);
        $this->save();
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'menunggu_dosen' => 'Menunggu Persetujuan Dosen',
            'menunggu_safety_officer' => 'Menunggu Safety Officer',
            'menunggu_kepala_lab' => 'Menunggu Kepala Lab',
            'menunggu_kaprodi' => 'Menunggu Kaprodi',
            'disetujui' => 'Disetujui',
            'disetujui_final' => 'Disetujui Final (Kaprodi)',
            'ditolak' => 'Ditolak',
            default => 'Tidak Diketahui',
        };
    }

    public function getKategoriResikoLabel(): ?string
    {
        if (! $this->kategori_resiko_dosen) {
            return null;
        }

        return match ($this->kategori_resiko_dosen) {
            'tinggi' => 'Beresiko Tinggi',
            'sedang' => 'Beresiko Sedang',
            'rendah' => 'Beresiko Rendah',
            default => null,
        };
    }

    public function isMasihBerlaku()
    {
        if (! $this->batas_waktu_peminjaman) {
            return true;
        }

        return now()->lte(Carbon::parse($this->batas_waktu_peminjaman));
    }

    public function getBatasWaktuPeminjamanFormatted()
    {
        if (! $this->batas_waktu_peminjaman) {
            return '-';
        }

        return Carbon::parse($this->batas_waktu_peminjaman)->format('d M Y');
    }

    public function isHampirExpired()
    {
        if (! $this->batas_waktu_peminjaman) {
            return false;
        }

        $sisaHari = (int) round(now()->diffInDays(Carbon::parse($this->batas_waktu_peminjaman), false));

        return $sisaHari > 0 && $sisaHari <= 30;
    }

    public function getSisaWaktuPeminjaman()
    {
        if (! $this->batas_waktu_peminjaman) {
            return '-';
        }

        $batasWaktu = Carbon::parse($this->batas_waktu_peminjaman);
        $sisaHari = (int) round(now()->diffInDays($batasWaktu, false));

        if ($sisaHari < 0) {
            return 'Sudah berakhir';
        } elseif ($sisaHari == 0) {
            return 'Hari ini';
        } elseif ($sisaHari == 1) {
            return '1 hari lagi';
        } else {
            return $sisaHari.' hari lagi';
        }
    }

    /**
     * Cek apakah bisa mengajukan ke Kaprodi
     */
    public function bisaAjukanKeKaprodi(): bool
    {
        return $this->status === 'disetujui'
            && $this->persetujuan_kepala_lab === true
            && is_null($this->kaprodi_id);
    }

    public function sudahDiajukanKeKaprodi(): bool
    {
        return ! is_null($this->kaprodi_id);
    }

    /**
     * Cek apakah bisa mengajukan perpanjangan
     */
    public function bisaAjukanPerpanjangan(): bool
    {
        return in_array($this->status, ['disetujui', 'disetujui_final'], true)
            && ! $this->isMasihBerlaku()
            && ! $this->pengajuan_perpanjangan;
    }

    /**
     * Cek apakah ada pengajuan perpanjangan yang pending
     */
    public function hasPendingPerpanjangan(): bool
    {
        return $this->pengajuan_perpanjangan === true
            && $this->persetujuan_perpanjangan_kaprodi === null;
    }

    /**
     * Update batas waktu setelah perpanjangan disetujui
     */
    public function approvePerpanjangan(int $durasi)
    {
        $this->batas_waktu_peminjaman_sebelumnya = $this->batas_waktu_peminjaman;
        $this->batas_waktu_peminjaman = now()->addMonths($durasi);
        $this->durasi_perpanjangan_disetujui = $durasi;
        $this->jumlah_perpanjangan = ($this->jumlah_perpanjangan ?? 0) + 1;
        $this->pengajuan_perpanjangan = false;
        $this->save();
    }

    /**
     * Get status perpanjangan label
     */
    public function getStatusPerpanjanganLabel(): string
    {
        if (! $this->pengajuan_perpanjangan) {
            return '';
        }

        if ($this->persetujuan_perpanjangan_kaprodi === true) {
            return 'Perpanjangan Disetujui';
        } elseif ($this->persetujuan_perpanjangan_kaprodi === false) {
            return 'Perpanjangan Ditolak';
        } else {
            return 'Menunggu Persetujuan Perpanjangan';
        }
    }

    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'draft' => 'status-draft',
            'menunggu_dosen' => 'status-menunggu_dosen',
            'menunggu_safety_officer' => 'status-menunggu_safety_officer',
            'menunggu_kepala_lab' => 'status-menunggu_kepala_lab',
            'menunggu_kaprodi' => 'status-menunggu_kaprodi',
            'disetujui' => 'status-disetujui',
            'disetujui_final' => 'status-disetujui_final',
            'ditolak' => 'status-ditolak',
            default => 'status-draft',
        };
    }

    /**
     * Get badge color untuk status
     */
    public function getStatusBadgeColor(): string
    {
        return match ($this->status) {
            'draft' => 'gray',
            'menunggu_dosen' => 'yellow',
            'menunggu_safety_officer' => 'blue',
            'menunggu_kepala_lab' => 'indigo',
            'menunggu_kaprodi' => 'blue',
            'disetujui' => 'green',
            'disetujui_final' => 'green',
            'ditolak' => 'red',
            default => 'gray',
        };
    }
}
