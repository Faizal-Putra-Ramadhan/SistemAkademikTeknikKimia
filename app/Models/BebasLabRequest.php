<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BebasLabRequest extends Model
{
    protected $table = 'bebas_lab_requests';

    protected $fillable = [
        'user_id',
        'user_nama',
        'risk_assessment_id',
        'status',
        'kepala_lab_approved_at',
        'tanggal_berlaku_dari',
        'tanggal_berlaku_sampai',
        'is_active',
        'periode',
    ];

    protected $casts = [
        'kepala_lab_approved_at' => 'datetime',
        'tanggal_berlaku_dari' => 'datetime',
        'tanggal_berlaku_sampai' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(DaftarUser::class, 'user_id');
    }

    public function riskAssessment(): BelongsTo
    {
        return $this->belongsTo(RiskAssessment::class, 'risk_assessment_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(BebasLabApproval::class, 'bebas_lab_request_id');
    }

    public function isFullyApproved(): bool
    {
        // Hitung total approval yang ada
        $totalApprovals = $this->approvals()->count();

        // Jika tidak ada approval, berarti belum disetujui
        if ($totalApprovals === 0) {
            return false;
        }

        // Hitung approval yang sudah disetujui
        $approvedCount = $this->approvals()->where('status', 'disetujui')->count();

        // Return true jika SEMUA approval sudah disetujui
        return $approvedCount === $totalApprovals;
    }

    /**
     * Cek apakah bebas lab masih berlaku
     */
    public function isMasihBerlaku(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if (! $this->tanggal_berlaku_sampai) {
            return false;
        }

        return now()->lte($this->tanggal_berlaku_sampai);
    }

    /**
     * Cek apakah mahasiswa punya peminjaman aktif
     */
    public function hasPeminjamanAktif(): bool
    {
        $user = $this->user;
        if (! $user) {
            return false;
        }

        // Cek peminjaman alat aktif (status: menunggu atau disetujui)
        $peminjamanAlat = \App\Models\PeminjamanAlat::where('user_nama', $user->Nama)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->exists();

        // Cek peminjaman ruangan aktif (status: menunggu_laboran, disetujui_laboran, menunggu_kepala_lab, disetujui)
        $peminjamanRuangan = \App\Models\PeminjamanRuangan::where('user_nama', $user->Nama)
            ->whereIn('status', ['menunggu_laboran', 'disetujui_laboran', 'menunggu_kepala_lab', 'disetujui'])
            ->exists();

        return $peminjamanAlat || $peminjamanRuangan;
    }

    /**
     * Set masa berlaku bebas lab (6 bulan dari sekarang)
     */
    public function setMasaBerlaku(): void
    {
        $this->tanggal_berlaku_dari = now();
        $this->tanggal_berlaku_sampai = now()->addMonths(6);
        $this->is_active = true;
        $this->save();
    }

    /**
     * Nonaktifkan bebas lab
     */
    public function deactivate(): void
    {
        $this->is_active = false;
        $this->save();
    }
}
