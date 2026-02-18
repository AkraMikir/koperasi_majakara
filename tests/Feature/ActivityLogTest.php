<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Activity Log Feature Tests
 *
 * Menguji:
 * 1. ActivityLogService mencatat dengan benar ke database
 * 2. Model scopes dan helper attributes berfungsi
 * 3. Halaman log nasabah dan admin hanya bisa diakses Admin Utama
 * 4. Filter & pagination berjalan di halaman log
 */
class ActivityLogTest extends TestCase
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
            'nama'              => 'Admin Utama Test',
            'email'             => 'admin.utama@test.com',
            'pin'               => 123456,
            'password'          => bcrypt('password123'),
            'nomor_hp'          => '081234567890',
            'foto'              => 'default-avatar.jpg',
            'role'              => 'admin_utama',
            'email_verified_at' => now(),
        ]);

        $this->adminOperasional = User::create([
            'nama'              => 'Admin Operasional Test',
            'email'             => 'admin.op@test.com',
            'pin'               => 567890,
            'password'          => bcrypt('password123'),
            'nomor_hp'          => '081234567891',
            'foto'              => 'default-avatar.jpg',
            'role'              => 'admin_operasional',
            'email_verified_at' => now(),
        ]);

        $this->nasabah = User::create([
            'nama'              => 'Nasabah Test',
            'email'             => 'nasabah@test.com',
            'pin'               => 111111,
            'password'          => bcrypt('password123'),
            'nomor_hp'          => '081234567892',
            'foto'              => 'default-avatar.jpg',
            'role'              => 'nasabah',
            'email_verified_at' => now(),
        ]);
    }

    // ==================== ActivityLogService: Core log() ====================

    public function test_service_creates_log_entry_when_user_authenticated(): void
    {
        $this->actingAs($this->nasabah);

        $service = app(ActivityLogService::class);
        $service->log(
            action: 'submit_setoran',
            module: 'tabungan',
            description: 'Mengajukan setoran tabungan Rp 100.000 via transfer',
            properties: ['nominal' => 100000, 'metode' => 'transfer'],
            subjectType: 'PengajuanTabungan',
            subjectId: 'T20240101001'
        );

        $this->assertDatabaseHas('activity_logs', [
            'user_id'   => $this->nasabah->id,
            'user_name' => 'Nasabah Test',
            'user_role' => 'nasabah',
            'action'    => 'submit_setoran',
            'module'    => 'tabungan',
        ]);
    }

    public function test_service_does_not_log_when_unauthenticated(): void
    {
        $service = app(ActivityLogService::class);
        $service->log('some_action', 'tabungan', 'desc');

        $this->assertDatabaseCount('activity_logs', 0);
    }

    public function test_service_stores_properties_as_json(): void
    {
        $this->actingAs($this->adminOperasional);

        app(ActivityLogService::class)->log(
            action: 'approve_setoran',
            module: 'tabungan',
            description: 'Menyetujui setoran',
            properties: ['nominal' => 500000, 'nasabah' => 'Budi']
        );

        $log = ActivityLog::first();
        $this->assertIsArray($log->properties);
        $this->assertEquals(500000, $log->properties['nominal']);
        $this->assertEquals('Budi', $log->properties['nasabah']);
    }

    public function test_service_does_not_throw_exception_on_error(): void
    {
        // Seharusnya tidak throw exception walau ada error internal
        $this->actingAs($this->nasabah);
        $service = app(ActivityLogService::class);

        $exceptionThrown = false;
        try {
            $service->log('test_action', 'tabungan', 'desc');
        } catch (\Throwable $e) {
            $exceptionThrown = true;
        }

        $this->assertFalse($exceptionThrown, 'ActivityLogService::log() seharusnya tidak melempar exception.');
    }

    // ==================== ActivityLogService: Helper Methods ====================

    public function test_log_submit_setoran_creates_correct_entry(): void
    {
        $this->actingAs($this->nasabah);

        app(ActivityLogService::class)->logSubmitSetoran('T001', 250000, 'transfer');

        $log = ActivityLog::first();
        $this->assertEquals('submit_setoran', $log->action);
        $this->assertEquals('tabungan', $log->module);
        $this->assertStringContainsString('250.000', $log->description);
        $this->assertStringContainsString('transfer', $log->description);
        $this->assertEquals('PengajuanTabungan', $log->subject_type);
        $this->assertEquals('T001', $log->subject_id);
    }

    public function test_log_approve_setoran_records_nasabah_name(): void
    {
        $this->actingAs($this->adminOperasional);

        app(ActivityLogService::class)->logApproveSetoran('T002', 300000, 'Budi Santoso');

        $log = ActivityLog::first();
        $this->assertEquals('approve_setoran', $log->action);
        $this->assertStringContainsString('Budi Santoso', $log->description);
        $this->assertEquals($this->adminOperasional->id, $log->user_id);
        $this->assertEquals('admin_operasional', $log->user_role);
    }

    public function test_log_reject_setoran_includes_reason(): void
    {
        $this->actingAs($this->adminOperasional);

        app(ActivityLogService::class)->logRejectSetoran('T003', 200000, 'Siti', 'Bukti tidak valid');

        $log = ActivityLog::first();
        $this->assertEquals('reject_setoran', $log->action);
        $this->assertStringContainsString('Bukti tidak valid', $log->description);
    }

    public function test_log_cairkan_pinjaman_records_correctly(): void
    {
        $this->actingAs($this->adminOperasional);

        app(ActivityLogService::class)->logCairkanPinjaman(99, 5000000, 'Ahmad Fauzi');

        $log = ActivityLog::first();
        $this->assertEquals('cairkan_pinjaman', $log->action);
        $this->assertEquals('pinjaman', $log->module);
        $this->assertStringContainsString('5.000.000', $log->description);
        $this->assertStringContainsString('Ahmad Fauzi', $log->description);
    }

    public function test_log_ubah_password_records_without_sensitive_data(): void
    {
        $this->actingAs($this->nasabah);

        app(ActivityLogService::class)->logUbahPassword();

        $log = ActivityLog::first();
        $this->assertEquals('ubah_password', $log->action);
        $this->assertEquals('akun', $log->module);
        // Pastikan password tidak tersimpan di properties
        $this->assertEmpty($log->properties);
    }

    public function test_log_ubah_pin_records_correctly(): void
    {
        $this->actingAs($this->nasabah);

        app(ActivityLogService::class)->logUbahPin();

        $log = ActivityLog::first();
        $this->assertEquals('ubah_pin', $log->action);
        $this->assertEquals('akun', $log->module);
        $this->assertEquals($this->nasabah->id, $log->user_id);
    }

    public function test_log_reset_pin_nasabah_records_correctly(): void
    {
        $this->actingAs($this->adminUtama);

        app(ActivityLogService::class)->logResetPin(5, 'Budi Santoso');

        $log = ActivityLog::first();
        $this->assertEquals('reset_pin_nasabah', $log->action);
        $this->assertEquals('nasabah', $log->module);
        $this->assertStringContainsString('Budi Santoso', $log->description);
        $this->assertEquals('admin_utama', $log->user_role);
    }

    public function test_log_master_data_action_formats_description(): void
    {
        $this->actingAs($this->adminUtama);

        app(ActivityLogService::class)->logMasterDataAction('create', 'Bunga 10%', 'MasterBungaPinjaman', 1);

        $log = ActivityLog::first();
        $this->assertEquals('create_master_data', $log->action);
        $this->assertEquals('master_data', $log->module);
        $this->assertStringContainsString('Menambahkan', $log->description);
        $this->assertStringContainsString('Bunga 10%', $log->description);
    }

    public function test_log_admin_operasional_action_create(): void
    {
        $this->actingAs($this->adminUtama);

        app(ActivityLogService::class)->logAdminOperasionalAction('create', 'Admin Baru', 3);

        $log = ActivityLog::first();
        $this->assertEquals('create_admin_operasional', $log->action);
        $this->assertEquals('master_data', $log->module);
        $this->assertStringContainsString('Admin Baru', $log->description);
        $this->assertEquals('AdminOperasional', $log->subject_type);
        $this->assertEquals('3', $log->subject_id);
    }

    // ==================== Model: Scopes ====================

    public function test_scope_for_nasabah_filters_correctly(): void
    {
        $this->actingAs($this->nasabah);
        app(ActivityLogService::class)->log('action_a', 'tabungan', 'desc');

        $this->actingAs($this->adminOperasional);
        app(ActivityLogService::class)->log('action_b', 'pinjaman', 'desc');

        $nasabahLogs = ActivityLog::forNasabah()->get();
        $this->assertCount(1, $nasabahLogs);
        $this->assertEquals('nasabah', $nasabahLogs->first()->user_role);
    }

    public function test_scope_for_admin_includes_both_admin_roles(): void
    {
        $this->actingAs($this->adminOperasional);
        app(ActivityLogService::class)->log('action_op', 'tabungan', 'desc');

        $this->actingAs($this->adminUtama);
        app(ActivityLogService::class)->log('action_utama', 'pinjaman', 'desc');

        $this->actingAs($this->nasabah);
        app(ActivityLogService::class)->log('action_nasabah', 'akun', 'desc');

        $adminLogs = ActivityLog::forAdmin()->get();
        $this->assertCount(2, $adminLogs);
    }

    public function test_scope_for_admin_operasional_filters_correctly(): void
    {
        $this->actingAs($this->adminOperasional);
        app(ActivityLogService::class)->log('op_action', 'tabungan', 'desc');

        $this->actingAs($this->adminUtama);
        app(ActivityLogService::class)->log('utama_action', 'tabungan', 'desc');

        $opLogs = ActivityLog::forAdminOperasional()->get();
        $this->assertCount(1, $opLogs);
        $this->assertEquals('admin_operasional', $opLogs->first()->user_role);
    }

    public function test_scope_for_module_filters_correctly(): void
    {
        $this->actingAs($this->nasabah);
        app(ActivityLogService::class)->log('action_1', 'tabungan', 'desc');
        app(ActivityLogService::class)->log('action_2', 'pinjaman', 'desc');
        app(ActivityLogService::class)->log('action_3', 'tabungan', 'desc');

        $tabunganLogs = ActivityLog::forModule('tabungan')->get();
        $this->assertCount(2, $tabunganLogs);
    }

    public function test_scope_in_date_range_filters_correctly(): void
    {
        $this->actingAs($this->nasabah);
        app(ActivityLogService::class)->log('action_today', 'tabungan', 'desc');

        // Buat log dengan tanggal kemarin menggunakan raw insert
        ActivityLog::create([
            'user_id'    => $this->nasabah->id,
            'user_name'  => 'Nasabah Test',
            'user_role'  => 'nasabah',
            'action'     => 'action_yesterday',
            'module'     => 'tabungan',
            'description' => 'desc',
            'created_at'  => now()->subDay(),
        ]);

        $todayLogs = ActivityLog::inDateRange(today()->toDateString(), today()->toDateString())->get();
        $this->assertCount(1, $todayLogs);
        $this->assertEquals('action_today', $todayLogs->first()->action);
    }

    // ==================== Model: Attributes ====================

    public function test_action_color_green_for_approve_actions(): void
    {
        $this->actingAs($this->adminOperasional);
        app(ActivityLogService::class)->log('approve_setoran', 'tabungan', 'desc');
        $this->assertEquals('green', ActivityLog::first()->action_color);
    }

    public function test_action_color_red_for_reject_actions(): void
    {
        $this->actingAs($this->adminOperasional);
        app(ActivityLogService::class)->log('reject_setoran', 'tabungan', 'desc');
        $this->assertEquals('red', ActivityLog::first()->action_color);
    }

    public function test_action_color_yellow_for_edit_actions(): void
    {
        $this->actingAs($this->adminUtama);
        app(ActivityLogService::class)->log('edit_transaksi_manual', 'tabungan', 'desc');
        $this->assertEquals('yellow', ActivityLog::first()->action_color);
    }

    public function test_action_color_blue_for_cairkan_action(): void
    {
        $this->actingAs($this->adminOperasional);
        app(ActivityLogService::class)->log('cairkan_pinjaman', 'pinjaman', 'desc');
        $this->assertEquals('blue', ActivityLog::first()->action_color);
    }

    // ==================== Access Control: Halaman Log ====================

    public function test_unauthenticated_user_cannot_access_log_nasabah(): void
    {
        $response = $this->get(route('admin.activity-log.nasabah'));
        $response->assertRedirect(route('login'));
    }

    public function test_unauthenticated_user_cannot_access_log_admin(): void
    {
        $response = $this->get(route('admin.activity-log.admin-operasional'));
        $response->assertRedirect(route('login'));
    }

    public function test_nasabah_cannot_access_log_nasabah_page(): void
    {
        $response = $this->actingAs($this->nasabah)
            ->get(route('admin.activity-log.nasabah'));
        $response->assertStatus(403);
    }

    public function test_nasabah_cannot_access_log_admin_page(): void
    {
        $response = $this->actingAs($this->nasabah)
            ->get(route('admin.activity-log.admin-operasional'));
        $response->assertStatus(403);
    }

    public function test_admin_operasional_cannot_access_log_nasabah_page(): void
    {
        $response = $this->actingAs($this->adminOperasional)
            ->get(route('admin.activity-log.nasabah'));
        $response->assertStatus(403);
    }

    public function test_admin_operasional_cannot_access_log_admin_page(): void
    {
        $response = $this->actingAs($this->adminOperasional)
            ->get(route('admin.activity-log.admin-operasional'));
        $response->assertStatus(403);
    }

    public function test_admin_utama_can_access_log_nasabah_page(): void
    {
        $response = $this->actingAs($this->adminUtama)
            ->get(route('admin.activity-log.nasabah'));
        $response->assertStatus(200);
    }

    public function test_admin_utama_can_access_log_admin_page(): void
    {
        $response = $this->actingAs($this->adminUtama)
            ->get(route('admin.activity-log.admin-operasional'));
        $response->assertStatus(200);
    }

    // ==================== Halaman Log: Data tampil ====================

    public function test_log_nasabah_page_shows_nasabah_activity(): void
    {
        // Buat log nasabah
        ActivityLog::create([
            'user_id'     => $this->nasabah->id,
            'user_name'   => 'Nasabah Test',
            'user_role'   => 'nasabah',
            'action'      => 'submit_setoran',
            'module'      => 'tabungan',
            'description' => 'Mengajukan setoran tabungan Rp 500.000',
            'created_at'  => now(),
        ]);

        $response = $this->actingAs($this->adminUtama)
            ->get(route('admin.activity-log.nasabah'));

        $response->assertStatus(200);
        $response->assertSee('Mengajukan setoran tabungan Rp 500.000');
        $response->assertSee('Nasabah Test');
    }

    public function test_log_admin_page_shows_admin_activity(): void
    {
        // Buat log admin operasional — properties sebagai array (model akan json_encode otomatis via cast)
        ActivityLog::create([
            'user_id'     => $this->adminOperasional->id,
            'user_name'   => 'Admin Operasional Test',
            'user_role'   => 'admin_operasional',
            'action'      => 'approve_setoran',
            'module'      => 'tabungan',
            'description' => 'Menyetujui setoran Rp 500.000 dari nasabah Budi',
            'properties'  => ['nasabah' => 'Budi', 'nominal' => 500000],
            'ip_address'  => '127.0.0.1',
            'created_at'  => now(),
        ]);

        $response = $this->actingAs($this->adminUtama)
            ->get(route('admin.activity-log.admin-operasional'));

        $response->assertStatus(200);
        $response->assertSee('Menyetujui setoran Rp 500.000 dari nasabah Budi');
        $response->assertSee('Admin Operasional Test');
    }

    public function test_log_nasabah_page_does_not_show_admin_logs(): void
    {
        // Log nasabah
        ActivityLog::create([
            'user_id'     => $this->nasabah->id,
            'user_name'   => 'Nasabah Test',
            'user_role'   => 'nasabah',
            'action'      => 'submit_setoran',
            'module'      => 'tabungan',
            'description' => 'Log dari nasabah',
            'created_at'  => now(),
        ]);

        // Log admin
        ActivityLog::create([
            'user_id'     => $this->adminOperasional->id,
            'user_name'   => 'Admin Operasional Test',
            'user_role'   => 'admin_operasional',
            'action'      => 'approve_setoran',
            'module'      => 'tabungan',
            'description' => 'Log dari admin operasional',
            'created_at'  => now(),
        ]);

        $response = $this->actingAs($this->adminUtama)
            ->get(route('admin.activity-log.nasabah'));

        $response->assertSee('Log dari nasabah');
        $response->assertDontSee('Log dari admin operasional');
    }

    public function test_log_page_filter_by_module_works(): void
    {
        ActivityLog::create([
            'user_id' => $this->nasabah->id, 'user_name' => 'Nasabah Test',
            'user_role' => 'nasabah', 'action' => 'submit_setoran',
            'module' => 'tabungan', 'description' => 'log tabungan', 'created_at' => now(),
        ]);
        ActivityLog::create([
            'user_id' => $this->nasabah->id, 'user_name' => 'Nasabah Test',
            'user_role' => 'nasabah', 'action' => 'submit_pengajuan_pinjaman',
            'module' => 'pinjaman', 'description' => 'log pinjaman', 'created_at' => now(),
        ]);

        $response = $this->actingAs($this->adminUtama)
            ->get(route('admin.activity-log.nasabah', ['module' => 'tabungan']));

        $response->assertStatus(200);
        $response->assertSee('log tabungan');
        $response->assertDontSee('log pinjaman');
    }

    public function test_log_page_search_by_name_works(): void
    {
        ActivityLog::create([
            'user_id' => $this->nasabah->id, 'user_name' => 'Nasabah Test',
            'user_role' => 'nasabah', 'action' => 'submit_setoran',
            'module' => 'tabungan', 'description' => 'desc dari Nasabah Test', 'created_at' => now(),
        ]);

        $response = $this->actingAs($this->adminUtama)
            ->get(route('admin.activity-log.nasabah', ['search' => 'Nasabah Test']));

        $response->assertStatus(200);
        $response->assertSee('desc dari Nasabah Test');
    }

    public function test_log_page_shows_statistics(): void
    {
        ActivityLog::create([
            'user_id' => $this->nasabah->id, 'user_name' => 'Nasabah Test',
            'user_role' => 'nasabah', 'action' => 'submit_setoran',
            'module' => 'tabungan', 'description' => 'desc', 'created_at' => now(),
        ]);

        $response = $this->actingAs($this->adminUtama)
            ->get(route('admin.activity-log.nasabah'));

        $response->assertStatus(200);
        // Halaman harus menampilkan angka statistik
        $response->assertSee('Hari Ini');
        $response->assertSee('Minggu Ini');
        $response->assertSee('Bulan Ini');
        $response->assertSee('Total');
    }

    // ==================== Integrasi: Multiple logs ====================

    public function test_multiple_log_entries_are_stored_correctly(): void
    {
        $this->actingAs($this->nasabah);
        $service = app(ActivityLogService::class);

        $service->logSubmitSetoran('T001', 100000, 'transfer');
        $service->logSubmitPenarikan('T002', 50000, 'tunai');
        $service->logUbahPassword();

        $this->assertDatabaseCount('activity_logs', 3);
        $this->assertDatabaseHas('activity_logs', ['action' => 'submit_setoran']);
        $this->assertDatabaseHas('activity_logs', ['action' => 'submit_penarikan']);
        $this->assertDatabaseHas('activity_logs', ['action' => 'ubah_password']);
    }

    public function test_admin_and_nasabah_logs_stored_separately(): void
    {
        $this->actingAs($this->nasabah);
        app(ActivityLogService::class)->logSubmitSetoran('T001', 100000, 'transfer');

        $this->actingAs($this->adminOperasional);
        app(ActivityLogService::class)->logApproveSetoran('T001', 100000, 'Nasabah Test');

        $this->assertDatabaseCount('activity_logs', 2);
        $this->assertEquals(1, ActivityLog::forNasabah()->count());
        $this->assertEquals(1, ActivityLog::forAdmin()->count());
    }
}
