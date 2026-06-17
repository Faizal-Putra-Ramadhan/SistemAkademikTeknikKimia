<?php

namespace App\Models\Traits;

use App\Models\DaftarUser;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasLinkedAccounts
{
    /**
     * Akun parent (akun utama)
     */
    public function parentUser(): BelongsTo
    {
        return $this->belongsTo(DaftarUser::class, 'parent_user_id');
    }

    /**
     * Akun-akun yang ter-link (child accounts)
     */
    public function linkedAccounts(): HasMany
    {
        return $this->hasMany(DaftarUser::class, 'parent_user_id');
    }

    /**
     * Dapatkan semua akun yang ter-link termasuk diri sendiri
     */
    public function getAllLinkedAccounts()
    {
        // Jika ini adalah parent account
        if ($this->is_primary && ! $this->parent_user_id) {
            return DaftarUser::where('id', $this->id)
                ->orWhere('parent_user_id', $this->id)
                ->get();
        }

        // Jika ini adalah child account
        if ($this->parent_user_id) {
            return DaftarUser::where('id', $this->parent_user_id)
                ->orWhere('parent_user_id', $this->parent_user_id)
                ->get();
        }

        // Jika tidak ada linking, return diri sendiri
        return collect([$this]);
    }

    /**
     * Dapatkan parent account (akun utama)
     */
    public function getMainAccount()
    {
        if ($this->parent_user_id) {
            return DaftarUser::find($this->parent_user_id);
        }

        return $this;
    }

    /**
     * Check apakah user ini memiliki role tertentu di linked accounts
     */
    public function hasRoleInLinkedAccounts(string $role): bool
    {
        return $this->getAllLinkedAccounts()
            ->pluck('Role_User')
            ->contains($role);
    }

    /**
     * Dapatkan akun dengan role tertentu dari linked accounts
     */
    public function getLinkedAccountByRole(string $role)
    {
        return $this->getAllLinkedAccounts()
            ->firstWhere('Role_User', $role);
    }

    /**
     * Check apakah user ini adalah akun utama
     */
    public function isPrimaryAccount(): bool
    {
        return $this->is_primary && ! $this->parent_user_id;
    }

    /**
     * Check apakah user memiliki linked accounts
     */
    public function hasLinkedAccounts(): bool
    {
        return $this->getAllLinkedAccounts()->count() > 1;
    }

    /**
     * Link akun lain ke akun ini
     */
    public function linkAccount(DaftarUser $user)
    {
        // Pastikan akun ini adalah primary account
        if (! $this->isPrimaryAccount()) {
            throw new \Exception('Hanya primary account yang bisa me-link akun lain');
        }

        // Update user yang akan di-link
        $user->update([
            'parent_user_id' => $this->id,
            'is_primary' => false,
        ]);

        return $user;
    }

    /**
     * Unlink akun dari parent
     */
    public function unlinkAccount()
    {
        if ($this->parent_user_id) {
            $this->update([
                'parent_user_id' => null,
                'is_primary' => true,
            ]);
        }

        return $this;
    }
}
