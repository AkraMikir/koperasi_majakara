<?php

namespace Tests\Feature;

use App\Models\UserTemp;
use App\Models\NasabahTemp;
use App\Models\DataRekTemp;
use App\Models\MasterDataBankRegis;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterBankSelectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_bank_selection_step_1_substep_5(): void
    {
        // Seed banks
        MasterDataBankRegis::create(['nama_bank' => 'BCA', 'kode_bank' => '014', 'status' => true]);
        MasterDataBankRegis::create(['nama_bank' => 'Mandiri', 'kode_bank' => '008', 'status' => true]);

        // Simulasikan session registrasi
        $userTemp = UserTemp::create([
            'nama' => 'Test User',
            'email' => 'test@example.com',
            'nomor_hp' => '081234567890',
            'password' => bcrypt('password123'),
        ]);

        $nasabahTemp = NasabahTemp::create([
            'user_id' => $userTemp->id,
            'no_kk' => '1234567890123456',
            'alamat' => 'Test Alamat',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
        ]);

        $response = $this->withSession([
            'register_user_temp_id' => $userTemp->id,
            'register_nasabah_temp_id' => $nasabahTemp->id,
            'register_session_id' => 'test-session-id',
        ])->get(route('register', ['step' => 1, 'substep' => 5]));

        $response->assertStatus(200);
        $response->assertSee('BCA');
        $response->assertSee('Mandiri');
        $response->assertSee('Lainnya');
    }

    public function test_can_submit_standard_bank_selection(): void
    {
        $userTemp = UserTemp::create([
            'nama' => 'Test User',
            'email' => 'test@example.com',
            'nomor_hp' => '081234567890',
            'password' => bcrypt('password123'),
        ]);

        $nasabahTemp = NasabahTemp::create([
            'user_id' => $userTemp->id,
            'no_kk' => '1234567890123456',
            'alamat' => 'Test Alamat',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
        ]);

        $response = $this->withSession([
            'register_user_temp_id' => $userTemp->id,
            'register_nasabah_temp_id' => $nasabahTemp->id,
            'register_session_id' => 'test-session-id',
        ])->post(route('register', ['step' => 1, 'substep' => 5]), [
            'no_rekening' => '1234567890',
            'nama_pemilik_rekening' => 'Test User',
            'jenis_atm' => 'BCA',
        ]);

        $response->assertRedirect(route('register', ['step' => 1, 'substep' => 6]));

        // Assert database record exists
        $this->assertDatabaseHas('tbl_data_rek_temp', [
            'nasabah_id' => $nasabahTemp->id,
            'no_rekening' => '1234567890',
            'nama_pemilik_rekening' => 'Test User',
            'jenis_atm' => 'BCA',
        ]);
    }

    public function test_can_submit_custom_bank_selection_with_long_name(): void
    {
        $userTemp = UserTemp::create([
            'nama' => 'Test User',
            'email' => 'test@example.com',
            'nomor_hp' => '081234567890',
            'password' => bcrypt('password123'),
        ]);

        $nasabahTemp = NasabahTemp::create([
            'user_id' => $userTemp->id,
            'no_kk' => '1234567890123456',
            'alamat' => 'Test Alamat',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
        ]);

        $customBankName = 'Bank Pembangunan Daerah Jawa Barat dan Banten (BJB) Syariah';

        $response = $this->withSession([
            'register_user_temp_id' => $userTemp->id,
            'register_nasabah_temp_id' => $nasabahTemp->id,
            'register_session_id' => 'test-session-id',
        ])->post(route('register', ['step' => 1, 'substep' => 5]), [
            'no_rekening' => '1234567890',
            'nama_pemilik_rekening' => 'Test User',
            'jenis_atm' => $customBankName, // Custom long name
        ]);

        $response->assertRedirect(route('register', ['step' => 1, 'substep' => 6]));

        // Assert database record has the long name and didn't crash
        $this->assertDatabaseHas('tbl_data_rek_temp', [
            'nasabah_id' => $nasabahTemp->id,
            'no_rekening' => '1234567890',
            'nama_pemilik_rekening' => 'Test User',
            'jenis_atm' => $customBankName,
        ]);
    }

    public function test_can_view_step_2_without_banks_variable_error(): void
    {
        $userTemp = UserTemp::create([
            'nama' => 'Test User',
            'email' => 'test@example.com',
            'nomor_hp' => '081234567890',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->withSession([
            'register_user_temp_id' => $userTemp->id,
            'register_phone' => '081234567890',
            'register_session_id' => 'test-session-id',
        ])->get(route('register', ['step' => 2]));

        $response->assertStatus(200);
    }

    public function test_nik_must_be_exactly_16_digits(): void
    {
        $userTemp = UserTemp::create([
            'nama' => 'Test User',
            'email' => 'test@example.com',
            'nomor_hp' => '081234567890',
            'password' => bcrypt('password123'),
        ]);

        $nasabahTemp = NasabahTemp::create([
            'user_id' => $userTemp->id,
            'no_kk' => '1234567890123456',
            'alamat' => 'Test Alamat',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
        ]);

        // Submit NIK that is too short (15 digits)
        $response = $this->withSession([
            'register_user_temp_id' => $userTemp->id,
            'register_nasabah_temp_id' => $nasabahTemp->id,
            'register_session_id' => 'test-session-id',
        ])->post(route('register', ['step' => 1, 'substep' => 2]), [
            'nik' => '123456789012345', // 15 digits
            'nama_lengkap_ktp' => 'Test Name',
            'tempat_lahir_ktp' => 'Jakarta',
            'tanggal_lahir_ktp' => '1990-01-01',
            'rt_rw' => '001/002',
            'kel_desa' => 'Test Kel',
            'kecamatan' => 'Test Kec',
            'jenis_kelamin_ktp' => 'Laki-laki',
            'file_ktp' => 'dummy_ktp.jpg',
        ]);

        $response->assertSessionHasErrors(['nik']);

        // Submit NIK that is too long (17 digits)
        $response = $this->withSession([
            'register_user_temp_id' => $userTemp->id,
            'register_nasabah_temp_id' => $nasabahTemp->id,
            'register_session_id' => 'test-session-id',
        ])->post(route('register', ['step' => 1, 'substep' => 2]), [
            'nik' => '12345678901234567', // 17 digits
            'nama_lengkap_ktp' => 'Test Name',
            'tempat_lahir_ktp' => 'Jakarta',
            'tanggal_lahir_ktp' => '1990-01-01',
            'rt_rw' => '001/002',
            'kel_desa' => 'Test Kel',
            'kecamatan' => 'Test Kec',
            'jenis_kelamin_ktp' => 'Laki-laki',
            'file_ktp' => 'dummy_ktp.jpg',
        ]);

        $response->assertSessionHasErrors(['nik']);

        // Submit NIK with non-numeric characters
        $response = $this->withSession([
            'register_user_temp_id' => $userTemp->id,
            'register_nasabah_temp_id' => $nasabahTemp->id,
            'register_session_id' => 'test-session-id',
        ])->post(route('register', ['step' => 1, 'substep' => 2]), [
            'nik' => '123456789012345a', // contains letter 'a'
            'nama_lengkap_ktp' => 'Test Name',
            'tempat_lahir_ktp' => 'Jakarta',
            'tanggal_lahir_ktp' => '1990-01-01',
            'rt_rw' => '001/002',
            'kel_desa' => 'Test Kel',
            'kecamatan' => 'Test Kec',
            'jenis_kelamin_ktp' => 'Laki-laki',
            'file_ktp' => 'dummy_ktp.jpg',
        ]);

        $response->assertSessionHasErrors(['nik']);

        // Submit valid NIK (16 digits)
        $response = $this->withSession([
            'register_user_temp_id' => $userTemp->id,
            'register_nasabah_temp_id' => $nasabahTemp->id,
            'register_session_id' => 'test-session-id',
        ])->post(route('register', ['step' => 1, 'substep' => 2]), [
            'nik' => '1234567890123456', // 16 digits
            'nama_lengkap_ktp' => 'Test Name',
            'tempat_lahir_ktp' => 'Jakarta',
            'tanggal_lahir_ktp' => '1990-01-01',
            'rt_rw' => '001/002',
            'kel_desa' => 'Test Kel',
            'kecamatan' => 'Test Kec',
            'jenis_kelamin_ktp' => 'Laki-laki',
            'file_ktp' => 'dummy_ktp.jpg',
        ]);

        $response->assertSessionHasNoErrors();
    }
}
