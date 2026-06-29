<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SettingsStruk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingsStrukController extends Controller
{
    public function index()
    {
        $settings = SettingsStruk::getSettings();
        
        // Data dummy untuk preview
        $dummyTabungan = [
            'jenis_trans' => 'NABUNG',
            'no_struk' => SettingsStruk::generateNoStruk(),
            'tanggal' => now()->format('d/m/Y H:i'),
            'nama_anggota' => 'John Doe',
            'nominal' => 500000,
            'saldo_sebelum' => 1500000,
            'saldo_sekarang' => 2000000,
        ];
        
        $dummyPinjaman = [
            'jenis_trans' => 'PENCAIRAN',
            'no_struk' => SettingsStruk::generateNoStruk(),
            'tanggal' => now()->format('d/m/Y H:i'),
            'nama_anggota' => 'John Doe',
            'no_pinjaman' => 'PNJ-20260501-001',
            'jumlah_pinjam' => 5000000,
            'bunga' => 12,
            'lama_pinjam' => 12,
            'angsuran_pertama' => 445231,
        ];
        
        $dummyDeposito = [
            'jenis_trans' => 'PENCAIRAN SESUDAH TEMPO',
            'no_struk' => SettingsStruk::generateNoStruk(),
            'tanggal' => now()->format('d/m/Y H:i'),
            'nama_anggota' => 'John Doe',
            'no_deposito' => 'DEP-20260101-001',
            'nominal_awal' => 10000000,
            'bunga' => 5,
            'jangka_waktu' => 6,
            'jatuh_tempo' => '01/07/2026',
            'nominal_akhir' => 10250000,
        ];
        
        return view('admin.settings.struk', compact(
            'settings', 
            'dummyTabungan', 
            'dummyPinjaman', 
            'dummyDeposito'
        ));
    }

    public function updateHeader(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_koperasi' => 'required|string|max:255',
            'alamat_koperasi' => 'required|string|max:1000',
            'no_telp' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'nama_pt' => 'required|string|max:255',
            'format_no_struk' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $settings = SettingsStruk::getSettings();
        $settings->update([
            'nama_koperasi' => $request->nama_koperasi,
            'alamat_koperasi' => $request->alamat_koperasi,
            'no_telp' => $request->no_telp,
            'email' => $request->email,
            'website' => $request->website,
            'nama_pt' => $request->nama_pt,
            'format_no_struk' => $request->format_no_struk,
        ]);

        return redirect()->back()->with('success', 'Header settings berhasil diupdate.');
    }

    public function updateSyaratGadai(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'syarat_ketentuan_gadai' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $settings = SettingsStruk::getSettings();
        $settings->update([
            'syarat_ketentuan_gadai' => $request->syarat_ketentuan_gadai,
        ]);

        return redirect()->back()->with('success', 'Syarat & ketentuan gadai berhasil diupdate.');
    }

    public function updateSyaratPinjaman(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'syarat_ketentuan_pinjaman' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $settings = SettingsStruk::getSettings();
        $settings->update([
            'syarat_ketentuan_pinjaman' => $request->syarat_ketentuan_pinjaman,
        ]);

        return redirect()->back()->with('success', 'Syarat & ketentuan pinjaman berhasil diupdate.');
    }

    public function updateInfoBoxPinjaman(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'info_box_pinjaman' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $settings = SettingsStruk::getSettings();
        $settings->update([
            'info_box_pinjaman' => $request->info_box_pinjaman,
        ]);

        return redirect()->back()->with('success', 'Info box pinjaman berhasil diupdate.');
    }

    public function updateSyaratGadaiElektronik(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'syarat_ketentuan_gadai_elektronik' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $settings = SettingsStruk::getSettings();
        $settings->update([
            'syarat_ketentuan_gadai_elektronik' => $request->syarat_ketentuan_gadai_elektronik,
        ]);

        return redirect()->back()->with('success', 'Syarat & ketentuan gadai elektronik berhasil diupdate.');
    }

    public function updateSyaratGadaiKendaraan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'syarat_ketentuan_gadai_kendaraan' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $settings = SettingsStruk::getSettings();
        $settings->update([
            'syarat_ketentuan_gadai_kendaraan' => $request->syarat_ketentuan_gadai_kendaraan,
        ]);

        return redirect()->back()->with('success', 'Syarat & ketentuan gadai kendaraan berhasil diupdate.');
    }

    public function updateInfoBoxGadaiElektronik(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'info_box_gadai_elektronik' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $settings = SettingsStruk::getSettings();
        $settings->update([
            'info_box_gadai_elektronik' => $request->info_box_gadai_elektronik,
        ]);

        return redirect()->back()->with('success', 'Info box gadai elektronik berhasil diupdate.');
    }

    public function updateInfoBoxGadaiKendaraan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'info_box_gadai_kendaraan' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $settings = SettingsStruk::getSettings();
        $settings->update([
            'info_box_gadai_kendaraan' => $request->info_box_gadai_kendaraan,
        ]);

        return redirect()->back()->with('success', 'Info box gadai kendaraan berhasil diupdate.');
    }

    /**
     * Update Extra Nilai Kehilangan (SAJA yang tersisa dari settings gadai)
     */
    public function updateExtraKehilangan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'extra_nilai_kehilangan' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $settings = SettingsStruk::getSettings();
        $settings->update([
            'extra_nilai_kehilangan' => $request->extra_nilai_kehilangan,
        ]);

        return redirect()->back()->with('success', 'Extra nilai kehilangan berhasil diupdate.');
    }

    public function previewTabungan(Request $request)
    {
        $settings = new SettingsStruk($request->only([
            'nama_koperasi', 'alamat_koperasi', 'no_telp', 'email', 'website', 'nama_pt', 'format_no_struk'
        ]));
        
        $data = [
            'jenis_trans' => $request->input('jenis_trans', 'NABUNG'),
            'no_struk' => SettingsStruk::generateNoStruk(),
            'tanggal' => now()->format('d/m/Y H:i'),
            'nama_anggota' => 'John Doe',
            'nominal' => (float)$request->input('nominal', 500000),
            'saldo_sebelum' => (float)$request->input('saldo_sebelum', 1500000),
            'saldo_sekarang' => (float)$request->input('saldo_sebelum', 1500000) + ($request->input('jenis_trans') === 'NABUNG' ? (float)$request->input('nominal', 500000) : -(float)$request->input('nominal', 500000)),
        ];

        return view('admin.settings.partials.preview-tabungan', compact('settings', 'data'));
    }

    public function previewPinjaman(Request $request)
    {
        $settings = new SettingsStruk($request->only([
            'nama_koperasi', 'alamat_koperasi', 'no_telp', 'email', 'website', 'nama_pt', 'format_no_struk', 'syarat_ketentuan_pinjaman', 'info_box_pinjaman'
        ]));

        $jenisTrans = $request->input('jenis_trans', 'PENCAIRAN');
        $jumlahPinjam = (float)$request->input('jumlah_pinjam', 5000000);
        $lamaPinjam = (int)$request->input('lama_pinjam', 12);
        $bungaPercent = (float)$request->input('bunga', 12);

        $bungaBulan = ($jumlahPinjam * ($bungaPercent / 100)) / $lamaPinjam;
        $pokokBulan = $jumlahPinjam / $lamaPinjam;
        $angsuranPertama = $pokokBulan + $bungaBulan;

        $data = [
            'jenis_trans' => $jenisTrans,
            'no_struk' => SettingsStruk::generateNoStruk(),
            'tanggal' => now()->format('d/m/Y H:i'),
            'nama_anggota' => 'John Doe',
            'no_pinjaman' => 'PNJ-' . now()->format('Ymd') . '-001',
            'jumlah_pinjam' => $jumlahPinjam,
            'bunga' => $bungaPercent,
            'lama_pinjam' => $lamaPinjam,
            'angsuran_pertama' => $angsuranPertama,
        ];

        return view('admin.settings.partials.preview-pinjaman', compact('settings', 'data'));
    }

    public function previewDeposito(Request $request)
    {
        $settings = new SettingsStruk($request->only([
            'nama_koperasi', 'alamat_koperasi', 'no_telp', 'email', 'website', 'nama_pt', 'format_no_struk'
        ]));

        $nominalAwal = (float)$request->input('nominal_awal', 10000000);
        $jangkaWaktu = (int)$request->input('jangka_waktu', 6);
        $bungaPercent = (float)$request->input('bunga', 5);
        
        $totalBunga = ($nominalAwal * ($bungaPercent / 100) * $jangkaWaktu) / 12;
        $nominalAkhir = $nominalAwal + $totalBunga;

        $data = [
            'jenis_trans' => $request->input('jenis_trans', 'PENCAIRAN SESUDAH TEMPO'),
            'no_struk' => SettingsStruk::generateNoStruk(),
            'tanggal' => now()->format('d/m/Y H:i'),
            'nama_anggota' => 'John Doe',
            'no_deposito' => 'DEP-' . now()->format('Ymd') . '-001',
            'nominal_awal' => $nominalAwal,
            'bunga' => $bungaPercent,
            'jangka_waktu' => $jangkaWaktu,
            'jatuh_tempo' => now()->addMonths($jangkaWaktu)->format('d/m/Y'),
            'nominal_akhir' => $nominalAkhir,
        ];

        return view('admin.settings.partials.preview-deposito', compact('settings', 'data'));
    }

    public function previewGadai(Request $request)
    {
        $settings = new SettingsStruk($request->only([
            'nama_koperasi', 'alamat_koperasi', 'no_telp', 'email', 'website', 'nama_pt', 'format_no_struk', 'syarat_ketentuan_gadai'
        ]));

        $nominalDeal = (float)$request->input('nominal_deal', 15000000);
        $biayaInap = (float)$request->input('biaya_inap', 150000);
        
        $biayaJasa = $nominalDeal * 0.012; 
        
        $totalTagihan = $nominalDeal + $biayaJasa + $biayaInap;

        $data = [
            'jenis_trans' => $request->input('jenis_trans', 'AKTIF'),
            'no_struk' => SettingsStruk::generateNoStruk('GD'),
            'tanggal' => now()->format('d/m/Y H:i'),
            'nama_anggota' => 'John Doe',
            'no_gadai' => 'GD-123',
            'kategori' => 'Elektronik',
            'barang' => 'Laptop ASUS ROG Strix',
            'slot_kode' => 'EL-005',
            'tgl_mulai' => now()->format('d/m/Y'),
            'jatuh_tempo' => now()->addDays(30)->format('d/m/Y'),
            'status' => 'Active',
            'nominal_deal' => $nominalDeal,
            'biaya_jasa' => $biayaJasa,
            'biaya_inap' => $biayaInap,
            'denda_aktif' => 0,
            'total_tagihan' => $totalTagihan,
        ];

        return view('admin.settings.partials.preview-gadai', compact('settings', 'data'));
    }
}
