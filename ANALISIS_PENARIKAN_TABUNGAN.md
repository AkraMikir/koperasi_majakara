# Analisis Fitur Penarikan Tabungan

## 1. Ringkasan Alur

### Sisi Nasabah
1. **Halaman Penarikan** (`/nasabah/tabungan/penarikan`)  
   - Menampilkan saldo, pilihan metode (Tunai / Transfer), form nominal + data rekening (jika transfer).  
   - Validasi: nominal minimal, saldo cukup, metode wajib.

2. **Submit** → `POST nasabah/tabungan/submit-penarikan`  
   - Cek saldo via `getSaldoNasabah()`.  
   - Insert ke **`tbl_pengajuan_penarikan_tabungan`** (tanpa generate **`id`** → berpotensi error, lihat poin 4).  
   - Redirect ke Status Pengajuan Tarik.

3. **Status & Detail**  
   - Status: list pengajuan penarikan nasabah.  
   - Detail: view detail per pengajuan (ID, nominal, metode, bank/rekening, status).

### Sisi Admin
1. **Dashboard Tabungan**  
   - Card "Pengajuan Tarik" (jumlah pending).  
   - List "Pengajuan Tarik Terbaru" (pending).

2. **List Pengajuan** (`/admin/tabungan/pengajuan-tarik`)  
   - Filter, tabel pengajuan penarikan, link ke detail.

3. **Detail Pengajuan** (`/admin/tabungan/pengajuan-tarik/{id}`)  
   - Data nasabah, nominal, metode, bank/rekening, **saldo nasabah**, status.  
   - Jika status = Pending: form **Setujui** (untuk transfer: upload bukti TF + bank pengirim) dan **Tolak**.

4. **Approve** → `POST admin/tabungan/pengajuan-tarik/{id}/approve`  
   - Validasi: jika metode transfer → wajib file bukti + bank_pengirim.  
   - Cek saldo.  
   - Update pengajuan: `status = '2'`, simpan `foto_bukti_tf_admin`.  
   - Generate ID transaksi, insert ke **`trans_tabungan`** dengan `id_jns_transaksi` = PNR, `id_jns_via` = TF/TN.  
   - **Tidak ada pengecekan** apakah transaksi untuk pengajuan ini sudah pernah dibuat → risiko duplikat jika approve diklik dua kali.

5. **Reject** → update status = '3' + keterangan.

---

## 2. Database & Model

### Tabel `tbl_pengajuan_penarikan_tabungan`
| Kolom               | Tipe        | Keterangan                          |
|---------------------|------------|-------------------------------------|
| id                  | string(30) PK | **Tidak ada default/auto**          |
| id_anggota          | FK nasabah |                                     |
| tgl_pengajuan       | datetime   |                                     |
| nominal             | decimal(15,2) |                                  |
| metode_transfer     | string(50) nullable | 'tunai' / 'transfer'      |
| no_rekening         | string(50) nullable | Untuk transfer              |
| nama_bank           | string(100) nullable | Untuk transfer            |
| foto_bukti_tf_admin | string nullable | Diisi admin saat approve transfer |
| keterangan, keterangan_admin | text nullable |                |
| status              | enum('1','2','3') default '1' | 1=Pending, 2=Disetujui, 3=Ditolak |

### Tabel `trans_tabungan` (untuk penarikan)
- Record penarikan: `id_jns_transaksi` = PNR, `id_pengajuan_tarik` = id pengajuan.  
- Nominal disimpan **positif**; tampilan minus di view (sama seperti setoran).

### Model
- **PengajuanPenarikanTabungan**: `id` di `$fillable`, tapi **tidak pernah di-set** di controller/Seeder.  
- Relasi: `nasabah()`, `transTabungan()` (HasMany).

---

## 3. Konsistensi dengan Setoran

| Aspek            | Setoran                         | Penarikan                         |
|------------------|---------------------------------|-----------------------------------|
| ID pengajuan     | IdGenerator (tbl_pengajuan_tabungan, T, T/C, STR) | **Tidak di-generate** |
| Transaksi        | id_jns_transaksi STR, id_jns_via TF/TN | id_jns_transaksi PNR, id_jns_via TF/TN ✅ |
| Nominal di DB    | Positif                         | Positif ✅                         |
| Duplikat approve | Dicek (transTabungan->count())  | **Tidak dicek**                    |

---

## 4. Temuan & Risiko

### 4.1 **Kritis: ID pengajuan penarikan tidak di-generate**
- **Lokasi**: `Nasabah\TabunganController::submitPenarikan()` → `PengajuanPenarikanTabungan::create([...])` **tanpa kunci `'id'`**.
- **Dampak**: Tabel `tbl_pengajuan_penarikan_tabungan` memakai `id` string(30) primary key tanpa default. Insert tanpa `id` akan gagal (SQL 1364: Field 'id' doesn't have a default value).
- **Saran**: Generate ID sebelum create, misalnya:
  - `IdGenerator::generate('tbl_pengajuan_penarikan_tabungan', 'T', 'TF'/'TN', 'PNR')` (sesuai metode), lalu masukkan ke array create.

### 4.2 **Risiko: Duplikat transaksi saat approve**
- **Lokasi**: `Admin\TabunganController::approveTarik()`.
- **Dampak**: Jika admin mengirim form approve dua kali (atau double-submit), akan terbentuk **dua** record di `trans_tabungan` untuk satu pengajuan → saldo nasabah berkurang dua kali.
- **Saran**: Sebelum create transaksi, cek apakah pengajuan ini sudah punya transaksi:
  - `if ($pengajuan->transTabungan()->exists()) { return redirect()->back()->with('error', '...'); }`

### 4.3 **Konsistensi nominal penarikan di trans_tabungan**
- Saat create transaksi penarikan, nominal diambil dari `$pengajuan->nominal` (positif).  
- Perhitungan saldo (setoran − penarikan) dan tampilan (minus untuk penarikan) sudah konsisten. Tidak ada perubahan perlu selama nominal disimpan positif.

### 4.4 **Form penarikan: nominal sebelum submit**
- View `penarikan-tabungan.blade.php` sudah membersihkan nominal (hapus non-digit) di event submit sebelum kirim.  
- Validasi server: nominal required, minimal, dan saldo cukup. Sudah konsisten.

### 4.5 **Tampilan riwayat penarikan (nasabah)**
- Menggunakan `TransTabungan` dengan `jnsTransaksi` kode PNR, dengan relasi `jnsVia` untuk "Via".  
- Setelah penyesuaian sebelumnya (accessor `jenis`/`via`, eager load, `abs(nominal)`), tampilan daftar dan detail transaksi penarikan sudah selaras dengan setoran.

---

## 5. Rekomendasi Perbaikan (Prioritas)

1. **Wajib**: Generate **id** saat create pengajuan penarikan di `submitPenarikan()` (IdGenerator untuk tabel `tbl_pengajuan_penarikan_tabungan`, format konsisten dengan setoran).
2. **Penting**: Di **approveTarik()**, tambah pengecekan "sudah ada transaksi untuk pengajuan ini"; jika sudah ada, jangan buat transaksi baru dan beri pesan error/redirect.
3. **Opsional**: Bungkus approve dalam **DB::transaction()** (update pengajuan + create transaksi) agar tidak ada state setengah jalan jika salah satu gagal.
4. **Opsional**: Seeder `TabunganSeeder` yang create `PengajuanPenarikanTabungan` juga perlu diberi **id** yang di-generate (atau manual) agar konsisten dengan struktur tabel.

---

## 6. Daftar File Terkait

| File | Fungsi |
|------|--------|
| `app/Http/Controllers/Nasabah/TabunganController.php` | penarikanTabungan(), submitPenarikan(), statusPengajuanTarik(), detailPengajuanTarik() |
| `app/Http/Controllers/Admin/TabunganController.php` | pengajuanTarik(), detailPengajuanTarik(), approveTarik(), rejectTarik(), getSaldoNasabah() |
| `app/Models/PengajuanPenarikanTabungan.php` | Model pengajuan penarikan |
| `app/Models/TransTabungan.php` | Relasi pengajuanTarik(), accessor jenis/via |
| `resources/views/nasabah/tabungan/penarikan-tabungan.blade.php` | Form penarikan |
| `resources/views/nasabah/tabungan/status-pengajuan-tarik.blade.php` | Daftar status |
| `resources/views/nasabah/tabungan/detail-pengajuan-tarik.blade.php` | Detail pengajuan (nasabah) |
| `resources/views/admin/tabungan/pengajuan-tarik.blade.php` | List pengajuan (admin) |
| `resources/views/admin/tabungan/detail-pengajuan-tarik.blade.php` | Detail + approve/reject (admin) |
| `database/migrations/2024_01_01_000003_create_tabungan_tables.php` | Schema tbl_pengajuan_penarikan_tabungan |
| `routes/web.php` | Route penarikan (nasabah + admin) |

---

## 7. Kesimpulan

Alur penarikan tabungan (nasabah ajukan → admin approve/reject → transaksi masuk ke `trans_tabungan`, saldo turun) secara konsep sudah benar dan selaras dengan setoran. Dua hal yang perlu diperbaiki agar fitur stabil dan aman:

1. **Generate ID** untuk setiap pengajuan penarikan saat create (dan di seeder jika dipakai).  
2. **Cegah duplikat transaksi** saat approve (cek `transTabungan` untuk pengajuan tersebut, dan idealnya pakai transaksi DB).

Setelah dua poin di atas ditangani, fitur penarikan tabungan siap dipakai dan konsisten dengan arsitektur yang ada.
