<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Nasabah;
use App\Models\Darurat;
use App\Models\PengajuanPerubahanData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EmergencyContactLockTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Nasabah $nasabah;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'nama' => 'Nasabah Test',
            'email' => 'nasabah.test@example.com',
            'pin' => bcrypt('123456'),
            'password' => bcrypt('password123'),
            'nomor_hp' => '081234567890',
            'foto' => 'default-avatar.jpg',
            'role' => 'nasabah',
            'email_verified_at' => now(),
            'verified' => now(),
        ]);

        $this->nasabah = Nasabah::create([
            'user_id' => $this->user->id,
            'no_kk' => '1234567890123456',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1995-05-05',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Kebon Jeruk No. 15',
        ]);
    }

    /**
     * Test that a nasabah without emergency contact is redirected.
     */
    public function test_nasabah_without_emergency_contact_is_blocked_from_loans_and_pawn(): void
    {
        // Assert emergency contact relation is currently null
        $this->assertNull($this->nasabah->darurat);

        // Try accessing pawn and loan routes
        // Adjust these routes based on routes in web.php
        $routes = [
            route('nasabah.pinjaman.index'),
            route('nasabah.gadai_baru.index'),
        ];

        foreach ($routes as $url) {
            $response = $this->actingAs($this->user)->get($url);
            $response->assertRedirect(route('nasabah.profile', ['focus' => 'kontak-darurat']));
            $response->assertSessionHas('error');
        }
    }

    /**
     * Test validation rules for emergency contact edit.
     */
    public function test_validation_rules_for_emergency_contact(): void
    {
        $response = $this->actingAs($this->user)->post(route('nasabah.profile.update-request'), [
            'jenis_data' => 'kontak_darurat',
            'pin' => '123456',
            'nama_lengkap_darurat' => 'Ab', // < 3 characters
            'hubungan_peminjam' => 'A', // < 2 characters
            'no_telepon_darurat' => 'abc', // non-numeric
            'email_darurat' => 'invalid-email',
            'pekerjaan_darurat' => 'X', // < 3 characters
            'no_ktp_darurat' => '123456', // < 16 digits
            'alamat_darurat' => 'Short', // < 10 characters
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors([
            'nama_lengkap',
            'hubungan_peminjam',
            'no_telepon',
            'email',
            'pekerjaan',
            'no_ktp',
            'alamat',
        ]);
    }

    /**
     * Test submitting emergency contact when currently null creates a pending request.
     */
    public function test_nasabah_submits_emergency_contact_when_currently_null_creates_pending_request(): void
    {
        $this->assertNull($this->nasabah->darurat);

        $response = $this->actingAs($this->user)->post(route('nasabah.profile.update-request'), [
            'jenis_data' => 'kontak_darurat',
            'pin' => '123456',
            'nama_lengkap_darurat' => 'Jane Doe',
            'hubungan_peminjam' => 'Ibu Kandung',
            'no_telepon_darurat' => '081299998888',
            'email_darurat' => 'jane.doe@example.com',
            'pekerjaan_darurat' => 'Wiraswasta',
            'no_ktp_darurat' => '1234567890123456',
            'alamat_darurat' => 'Jl. Mawar Indah Blok B3 No. 12',
        ]);

        // Should redirect with success message about admin queue
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Pengajuan perubahan data berhasil dikirim! Menunggu persetujuan admin.');

        // Refresh model and assert record does not exist in database directly
        $this->nasabah->refresh();
        $this->assertNull($this->nasabah->darurat);

        // Assert pending changes request was created
        $this->assertEquals(1, PengajuanPerubahanData::count());
        $pengajuan = PengajuanPerubahanData::first();
        $this->assertEquals($this->nasabah->id, $pengajuan->id_nasabah);
        $this->assertEquals('kontak_darurat', $pengajuan->jenis_data);
        $this->assertEquals('pending', $pengajuan->status);
        $this->assertEquals('Jane Doe', $pengajuan->data_baru['nama_lengkap']);
    }

    /**
     * Test approval queue logic when emergency contact already exists.
     */
    public function test_nasabah_requires_approval_queue_when_updating_existing_emergency_contact(): void
    {
        // Seed initial emergency contact
        Darurat::create([
            'id_nasabah' => $this->nasabah->id,
            'nama_lengkap' => 'Jane Doe',
            'hubungan_peminjam' => 'Ibu Kandung',
            'no_telepon' => '081299998888',
            'alamat' => 'Jl. Mawar Indah Blok B3 No. 12',
            'pekerjaan' => 'Wiraswasta',
            'email' => 'jane.doe@example.com',
            'no_ktp' => '1234567890123456',
            'foto_ktp' => '',
        ]);

        $this->nasabah->refresh();
        $this->assertNotNull($this->nasabah->darurat);

        $response = $this->actingAs($this->user)->post(route('nasabah.profile.update-request'), [
            'jenis_data' => 'kontak_darurat',
            'pin' => '123456',
            'nama_lengkap_darurat' => 'John Doe',
            'hubungan_peminjam' => 'Ayah Kandung',
            'no_telepon_darurat' => '081277776666',
            'email_darurat' => 'john.doe@example.com',
            'pekerjaan_darurat' => 'Pegawai Swasta',
            'no_ktp_darurat' => '9876543210987654',
            'alamat_darurat' => 'Jl. Melati Raya No. 45 Jakarta',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Pengajuan perubahan data berhasil dikirim! Menunggu persetujuan admin.');

        // Refresh nasabah and assert database value HAS NOT changed directly
        $this->nasabah->refresh();
        $this->assertEquals('Jane Doe', $this->nasabah->darurat->nama_lengkap);

        // Assert pending changes request was created
        $this->assertEquals(1, PengajuanPerubahanData::count());
        $pengajuan = PengajuanPerubahanData::first();
        $this->assertEquals($this->nasabah->id, $pengajuan->id_nasabah);
        $this->assertEquals('kontak_darurat', $pengajuan->jenis_data);
        $this->assertEquals('pending', $pengajuan->status);
        $this->assertEquals('John Doe', $pengajuan->data_baru['nama_lengkap']);
    }

    /**
     * Test submitting emergency contact with empty email creates a pending request.
     */
    public function test_nasabah_submits_emergency_contact_with_empty_email_creates_pending_request(): void
    {
        $this->assertNull($this->nasabah->darurat);

        $response = $this->actingAs($this->user)->post(route('nasabah.profile.update-request'), [
            'jenis_data' => 'kontak_darurat',
            'pin' => '123456',
            'nama_lengkap_darurat' => 'Jane Doe',
            'hubungan_peminjam' => 'Ibu Kandung',
            'no_telepon_darurat' => '081299998888',
            'email_darurat' => '', // Empty email
            'pekerjaan_darurat' => 'Wiraswasta',
            'no_ktp_darurat' => '1234567890123456',
            'alamat_darurat' => 'Jl. Mawar Indah Blok B3 No. 12',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Pengajuan perubahan data berhasil dikirim! Menunggu persetujuan admin.');

        $this->assertEquals(1, PengajuanPerubahanData::count());
        $pengajuan = PengajuanPerubahanData::first();
        $this->assertEquals('-', $pengajuan->data_baru['email']); // Should fall back to '-' in database
    }

    /**
     * Test submitting emergency contact with formatted phone number (+62 or spaces/dashes).
     */
    public function test_nasabah_submits_emergency_contact_with_formatted_phone_creates_pending_request(): void
    {
        $this->assertNull($this->nasabah->darurat);

        $response = $this->actingAs($this->user)->post(route('nasabah.profile.update-request'), [
            'jenis_data' => 'kontak_darurat',
            'pin' => '123456',
            'nama_lengkap_darurat' => 'Jane Doe',
            'hubungan_peminjam' => 'Ibu Kandung',
            'no_telepon_darurat' => '+62 812-9999-8888', // Formatted
            'email_darurat' => '',
            'pekerjaan_darurat' => 'Wiraswasta',
            'no_ktp_darurat' => '1234567890123456',
            'alamat_darurat' => 'Jl. Mawar Indah Blok B3 No. 12',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Pengajuan perubahan data berhasil dikirim! Menunggu persetujuan admin.');

        $this->assertEquals(1, PengajuanPerubahanData::count());
        $pengajuan = PengajuanPerubahanData::first();
        $this->assertEquals('081299998888', $pengajuan->data_baru['no_telepon']); // Should be normalized to 081299998888
    }
}
