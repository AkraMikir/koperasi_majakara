<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * RBAC Feature Tests - Admin Utama vs Admin Operasional
 *
 * Berdasarkan RBAC_IMPLEMENTATION_SUMMARY.md
 * - Admin Utama: full access
 * - Admin Operasional: restricted (no CRUD tabungan/pinjaman manual, no CRUD master data, no manage nasabah)
 */
class RbacAdminAccessTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUtama;
    protected User $adminOperasional;
    protected User $nasabah;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedUsers();
    }

    protected function seedUsers(): void
    {
        $this->adminUtama = User::create([
            'nama' => 'Admin Utama',
            'email' => 'admin.utama@test.com',
            'pin' => 123456,
            'password' => bcrypt('password123'),
            'nomor_hp' => '081234567890',
            'foto' => 'default-avatar.jpg',
            'role' => 'admin_utama',
            'email_verified_at' => now(),
        ]);

        $this->adminOperasional = User::create([
            'nama' => 'Admin Operasional',
            'email' => 'admin.op@test.com',
            'pin' => 567890,
            'password' => bcrypt('password123'),
            'nomor_hp' => '081234567891',
            'foto' => 'default-avatar.jpg',
            'role' => 'admin_operasional',
            'email_verified_at' => now(),
        ]);

        $this->nasabah = User::create([
            'nama' => 'Nasabah Test',
            'email' => 'nasabah@test.com',
            'pin' => 111111,
            'password' => bcrypt('password123'),
            'nomor_hp' => '081234567892',
            'foto' => 'default-avatar.jpg',
            'role' => 'nasabah',
            'email_verified_at' => now(),
        ]);
    }

    // ==================== Unauthenticated ====================

    public function test_unauthenticated_user_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_unauthenticated_user_cannot_access_any_admin_route(): void
    {
        $routes = [
            route('admin.dashboard'),
            route('admin.tabungan.index'),
            route('admin.pinjaman.index'),
            route('admin.master-data.index'),
            route('admin.nasabah.index'),
            route('admin.laporan.index'),
        ];
        foreach ($routes as $url) {
            $response = $this->get($url);
            $response->assertRedirect(route('login'));
        }
    }

    // ==================== Nasabah cannot access admin ====================

    public function test_nasabah_gets_403_when_accessing_admin_dashboard(): void
    {
        $response = $this->actingAs($this->nasabah)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_nasabah_gets_403_when_accessing_admin_tabungan(): void
    {
        $response = $this->actingAs($this->nasabah)->get(route('admin.tabungan.index'));
        $response->assertStatus(403);
    }

    // ==================== Admin Operasional - RESTRICTED routes (must get 403) ====================

    public function test_admin_operasional_cannot_access_tabungan_create_transaksi(): void
    {
        $response = $this->actingAs($this->adminOperasional)
            ->get(route('admin.tabungan.create-transaksi'));
        $response->assertStatus(403);
    }

    public function test_admin_operasional_cannot_access_pinjaman_create(): void
    {
        $response = $this->actingAs($this->adminOperasional)
            ->get(route('admin.pinjaman.create-pinjaman'));
        $response->assertStatus(403);
    }

    public function test_admin_operasional_cannot_access_master_data_bunga_pinjaman_create(): void
    {
        $response = $this->actingAs($this->adminOperasional)
            ->get(route('admin.master-data.bunga-pinjaman.create'));
        $response->assertStatus(403);
    }

    public function test_admin_operasional_cannot_access_nasabah_reset_pin(): void
    {
        $response = $this->actingAs($this->adminOperasional)
            ->post(route('admin.nasabah.reset-pin', ['id' => 1]));
        $response->assertStatus(403);
    }

    public function test_admin_operasional_cannot_access_tabungan_transaksi_edit(): void
    {
        $response = $this->actingAs($this->adminOperasional)
            ->get(route('admin.tabungan.edit-transaksi', ['id' => 1]));
        $response->assertStatus(403);
    }

    public function test_admin_operasional_cannot_access_pinjaman_edit(): void
    {
        $response = $this->actingAs($this->adminOperasional)
            ->get(route('admin.pinjaman.edit-pinjaman', ['id' => 1]));
        $response->assertStatus(403);
    }

    public function test_admin_operasional_cannot_access_generate_random_pin(): void
    {
        $response = $this->actingAs($this->adminOperasional)
            ->get(route('admin.nasabah.generate-pin'));
        $response->assertStatus(403);
    }

    // ==================== Admin Operasional - ALLOWED routes (must get 200 or redirect with view) ====================

    public function test_admin_operasional_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->adminOperasional)->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    public function test_admin_operasional_can_access_tabungan_index(): void
    {
        $response = $this->actingAs($this->adminOperasional)->get(route('admin.tabungan.index'));
        $response->assertStatus(200);
    }

    public function test_admin_operasional_can_access_tabungan_transaksi_list(): void
    {
        $response = $this->actingAs($this->adminOperasional)->get(route('admin.tabungan.transaksi'));
        $response->assertStatus(200);
    }

    public function test_admin_operasional_can_access_pinjaman_index(): void
    {
        $response = $this->actingAs($this->adminOperasional)->get(route('admin.pinjaman.index'));
        $response->assertStatus(200);
    }

    public function test_admin_operasional_can_access_pinjaman_aktif_list(): void
    {
        $response = $this->actingAs($this->adminOperasional)->get(route('admin.pinjaman.pinjaman-aktif'));
        $response->assertStatus(200);
    }

    public function test_admin_operasional_can_access_master_data_index(): void
    {
        $response = $this->actingAs($this->adminOperasional)->get(route('admin.master-data.index'));
        $response->assertStatus(200);
    }

    public function test_admin_operasional_can_access_master_data_bunga_pinjaman_view(): void
    {
        $response = $this->actingAs($this->adminOperasional)
            ->get(route('admin.master-data.bunga-pinjaman.index'));
        $response->assertStatus(200);
    }

    public function test_admin_operasional_can_access_laporan_index(): void
    {
        $response = $this->actingAs($this->adminOperasional)->get(route('admin.laporan.index'));
        $response->assertStatus(200);
    }

    public function test_admin_operasional_can_access_nasabah_index(): void
    {
        $response = $this->actingAs($this->adminOperasional)->get(route('admin.nasabah.index'));
        $response->assertStatus(200);
    }

    public function test_admin_operasional_can_access_notifications(): void
    {
        $response = $this->actingAs($this->adminOperasional)->get(route('admin.notifications.index'));
        $response->assertStatus(200);
    }

    // ==================== Admin Utama - Full access ====================

    public function test_admin_utama_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->adminUtama)->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    public function test_admin_utama_can_access_tabungan_create_transaksi(): void
    {
        $response = $this->actingAs($this->adminUtama)->get(route('admin.tabungan.create-transaksi'));
        $response->assertStatus(200);
    }

    public function test_admin_utama_can_access_pinjaman_create(): void
    {
        $response = $this->actingAs($this->adminUtama)->get(route('admin.pinjaman.create-pinjaman'));
        $response->assertStatus(200);
    }

    public function test_admin_utama_can_access_master_data_bunga_pinjaman_create(): void
    {
        $response = $this->actingAs($this->adminUtama)->get(route('admin.master-data.bunga-pinjaman.create'));
        $response->assertStatus(200);
    }

    public function test_admin_utama_can_access_nasabah_index(): void
    {
        $response = $this->actingAs($this->adminUtama)->get(route('admin.nasabah.index'));
        $response->assertStatus(200);
    }

    public function test_admin_utama_can_access_generate_random_pin(): void
    {
        $response = $this->actingAs($this->adminUtama)->get(route('admin.nasabah.generate-pin'));
        $response->assertStatus(200);
    }

    // ==================== Admin Operasional Management (dalam Master Data) ====================

    public function test_admin_operasional_cannot_access_admin_operasional_management_create(): void
    {
        $response = $this->actingAs($this->adminOperasional)
            ->get(route('admin.master-data.admin-operasional.create'));
        $response->assertStatus(403);
    }

    public function test_admin_operasional_cannot_access_admin_operasional_management_index(): void
    {
        $response = $this->actingAs($this->adminOperasional)
            ->get(route('admin.master-data.admin-operasional.index'));
        $response->assertStatus(403);
    }

    public function test_admin_utama_can_access_admin_operasional_management_index(): void
    {
        $response = $this->actingAs($this->adminUtama)
            ->get(route('admin.master-data.admin-operasional.index'));
        $response->assertStatus(200);
    }

    public function test_admin_utama_can_access_admin_operasional_management_create(): void
    {
        $response = $this->actingAs($this->adminUtama)
            ->get(route('admin.master-data.admin-operasional.create'));
        $response->assertStatus(200);
    }
}
