<?php

namespace App\Services;

use App\Models\User;

class AdminPermissionService
{
    /**
     * Check if user is Admin Utama
     */
    public function isAdminUtama(?User $user): bool
    {
        return $user && $user->role === 'admin_utama';
    }

    /**
     * Check if user is Admin Operasional
     */
    public function isAdminOperasional(?User $user): bool
    {
        return $user && $user->role === 'admin_operasional';
    }

    /**
     * Check if user is any type of admin
     */
    public function isAdmin(?User $user): bool
    {
        return $user && in_array($user->role, ['admin_utama', 'admin_operasional']);
    }

    /**
     * Check if user can access a specific feature
     */
    public function canAccessFeature(?User $user, string $feature): bool
    {
        if (!$this->isAdmin($user)) {
            return false;
        }

        // Admin Utama has access to everything
        if ($this->isAdminUtama($user)) {
            return true;
        }

        // Define features accessible by Admin Operasional
        $operasionalFeatures = [
            'dashboard',
            'tabungan-view',
            'tabungan-approval',
            'pinjaman-view',
            'pinjaman-approval',
            'master-data-view',
            'laporan',
            'nasabah-view',
            'janji-temu',
            'notifications',
        ];

        return in_array($feature, $operasionalFeatures);
    }

    /**
     * Check if user can approve/reject Tabungan (setor/tarik)
     * Admin Utama: YES | Admin Operasional: YES
     */
    public function canApproveTabungan(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if user can CRUD Tabungan Transaksi Manual
     * Admin Utama: YES | Admin Operasional: NO
     */
    public function canCrudTabunganTransaksi(?User $user): bool
    {
        return $this->isAdminUtama($user);
    }

    /**
     * Check if user can process Janji Temu Tabungan
     * Admin Utama: YES | Admin Operasional: YES
     */
    public function canProcessJanjiTemuTabungan(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if user can view Saldo Nasabah
     * Admin Utama: YES | Admin Operasional: YES
     */
    public function canViewSaldoNasabah(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if user can approve/reject/cairkan Pinjaman
     * Admin Utama: YES | Admin Operasional: YES
     */
    public function canApprovePinjaman(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if user can CRUD Pinjaman Aktif (manual)
     * Admin Utama: YES | Admin Operasional: NO
     */
    public function canCrudPinjamanAktif(?User $user): bool
    {
        return $this->isAdminUtama($user);
    }

    /**
     * Check if user can approve/reject Pembayaran Pinjaman
     * Admin Utama: YES | Admin Operasional: YES
     */
    public function canApprovePembayaranPinjaman(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if user can do Pelunasan Dipercepat
     * Admin Utama: YES | Admin Operasional: NO
     */
    public function canPelunasanDipercepat(?User $user): bool
    {
        return $this->isAdminUtama($user);
    }

    /**
     * Check if user can view Master Data
     * Admin Utama: YES | Admin Operasional: YES (view only)
     */
    public function canViewMasterData(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if user can CRUD Master Data
     * Admin Utama: YES | Admin Operasional: YES
     */
    public function canCrudMasterData(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if user can CRUD Item Gadai
     * Admin Utama: YES | Admin Operasional: YES
     */
    public function canCrudItemGadai(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if user can view Laporan Keuangan
     * Admin Utama: YES | Admin Operasional: YES
     */
    public function canViewLaporan(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if user can view Nasabah list and details
     * Admin Utama: YES | Admin Operasional: YES (view only)
     */
    public function canViewNasabah(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if user can manage Nasabah (approve changes, reset PIN)
     * Admin Utama: YES | Admin Operasional: NO
     */
    public function canManageNasabah(?User $user): bool
    {
        return $this->isAdminUtama($user);
    }

    /**
     * Check if user can verify Nasabah
     * Admin Utama: YES | Admin Operasional: YES
     */
    public function canVerifyNasabah(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if user can approve Nasabah profile changes
     * Admin Utama: YES | Admin Operasional: YES
     */
    public function canApproveNasabahChanges(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if user can reset Nasabah PIN
     * Admin Utama: YES | Admin Operasional: NO
     */
    public function canResetNasabahPin(?User $user): bool
    {
        return $this->isAdminUtama($user);
    }

    /**
     * Check if user can access Janji Temu Universal
     * Admin Utama: YES | Admin Operasional: YES
     */
    public function canAccessJanjiTemu(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if user can manage Notifications
     * Admin Utama: YES | Admin Operasional: YES
     */
    public function canManageNotifications(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Get user role display name
     */
    public function getRoleDisplayName(?User $user): string
    {
        if (!$user) {
            return 'Guest';
        }

        return match($user->role) {
            'admin_utama' => 'Admin Utama',
            'admin_operasional' => 'Admin Operasional',
            'nasabah' => 'Nasabah',
            default => 'Unknown',
        };
    }

    /**
     * Get role badge color for UI
     */
    public function getRoleBadgeColor(?User $user): string
    {
        if (!$user) {
            return 'secondary';
        }

        return match($user->role) {
            'admin_utama' => 'danger',
            'admin_operasional' => 'warning',
            'nasabah' => 'info',
            default => 'secondary',
        };
    }
}
