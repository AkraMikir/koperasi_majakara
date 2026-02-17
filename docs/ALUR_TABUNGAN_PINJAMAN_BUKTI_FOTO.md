# Alur Tabungan Pinjaman dan Bukti Foto

Dokumen ini memetakan alur yang ada di project untuk pengajuan tabungan (setor dan penarikan), approval, pengajuan pinjaman, approval pinjaman, pengajuan pembayaran pinjaman, serta penggunaan tabel bukti foto.

---

## Tabel Ringkasan Alur

| No | Nama Alur | View Nasabah/Admin | Route Submit/Action | Tabel yang Diisi | Generate ID |
|----|-----------|--------------------|---------------------|------------------|--------------|
| 1 | Pengajuan setoran tabungan transfer | nasabah.tabungan.pengajuan-transfer | submitSetoran POST | tbl_pengajuan_tabungan, tbl_bukti_foto | IdGenerator tbl_pengajuan_tabungan T+TF+STR; tbl_bukti_foto T+TF+STR |
| 2 | Approval pengajuan setoran tabungan | admin.tabungan.detail-pengajuan-setor | approveSetor POST | trans_tabungan (id_pengajuan_setor), tbl_pengajuan_tabungan status 2 | IdGenerator trans_tabungan T+TF+STR |
| 3 | Pengajuan setoran tabungan janji temu cash | nasabah.tabungan.janji-temu | submitJanjiTemu POST | tbl_janji_temu_tabungan (jenis setoran implisit) | IdGenerator tbl_janji_temu_tabungan T+CS+JNJT |
| 4 | Approval setoran janji temu cash | admin janji temu detail | createTransFromJanjiTemu POST | trans_tabungan (id_janji_temu_tabungan), tbl_janji_temu_tabungan status 2, tbl_bukti_foto optional | IdGenerator trans_tabungan T+CS+STR; tbl_bukti_foto T+CS+JNJT |
| 5 | Pengajuan penarikan tabungan transfer | nasabah.tabungan.penarikan-tabungan | submitPenarikan POST metode transfer | tbl_pengajuan_penarikan_tabungan (metode_transfer transfer, no_rekening, nama_bank) | IdGenerator tbl_pengajuan_penarikan_tabungan T+TF+PNR |
| 6 | Approval pengajuan penarikan transfer | admin.tabungan.detail-pengajuan-tarik | approveTarik POST | tbl_pengajuan_penarikan_tabungan status 2 foto_bukti_tf_admin, trans_tabungan id_pengajuan_tarik | IdGenerator trans_tabungan T+TF+PNR |
| 7 | Pengajuan penarikan tabungan tunai janji temu | nasabah.tabungan.penarikan-tabungan | submitPenarikan POST metode tunai | tbl_pengajuan_penarikan_tabungan (metode tunai, lokasi_temu, tanggal/waktu), tbl_janji_temu_tabungan jenis penarikan | IdGenerator T+TN+PNR dan T+CS+JNJT |
| 8 | Approval penarikan tunai via janji temu | admin janji temu | createTransFromJanjiTemu POST jenis penarikan | trans_tabungan id_janji_temu_tabungan id_pengajuan_tarik; tbl_pengajuan_penarikan_tabungan status 2; tbl_janji_temu_tabungan status 2 | IdGenerator trans_tabungan T+CS+PNR |
| 9 | Pengajuan pinjaman transfer | nasabah pinjaman pengajuan form transfer | submitPengajuanTransfer POST | tbl_pengajuan_pinjaman (jenis_pencairan transfer) | IdGenerator tbl_pengajuan_pinjaman P+TF+PNJ |
| 10 | Approval pengajuan pinjaman transfer | admin.pinjaman.detail-pengajuan | approvePengajuan POST | tbl_pinjaman_h, tbl_pengajuan_pinjaman status 3 | IdGenerator tbl_pinjaman_h P+TF+DPNJM |
| 11 | Cairkan pinjaman transfer | admin.pinjaman.detail-pengajuan | cairkanPinjaman POST | tempo_pinjaman_b, tbl_bukti_foto owner_id pengajuan PNCR, tbl_pengajuan_pinjaman status 4 tgl_cair | IdGenerator tempo PT+TPNJM; tbl_bukti_foto P+TF+PNCR |
| 12 | Pengajuan pinjaman janji temu tunai | nasabah pinjaman janji temu | submitJanjiTemuPinjaman POST | tbl_pengajuan_pinjaman jenis_pencairan tunai, tbl_janji_temu_pinjaman id_pengajuan | IdGenerator P+TN+PNJ dan P+TN+JNJT |
| 13 | Approval dan cairkan pinjaman janji temu | admin janji temu detail pinjaman | prosesJanjiTemuPinjaman POST | tbl_janji_temu_pinjaman status 2; tbl_pinjaman_h; tbl_pengajuan_pinjaman status 4; tempo_pinjaman_b; optional tbl_bukti_foto owner janji temu | IdGenerator tbl_pinjaman_h P+TN+DPNJM; tempo PT+TPNJM |
| 14 | Pengajuan pembayaran pinjaman transfer | nasabah.pinjaman.pembayaran tab transfer | submitPembayaranTransfer POST | tbl_pengajuan_pembayaran_pinjaman (rekening_tujuan), tbl_bukti_foto owner_id pengajuan PMB | IdGenerator P+TF+PMB; tbl_bukti_foto P+TF+PMB |
| 15 | Approval pembayaran pinjaman transfer | admin.pinjaman.detail-pembayaran | approvePembayaran POST | tbl_pengajuan_pembayaran_pinjaman status 3; tempo_pinjaman_b jumlah_terbayar denda status_bayar tgl_bayar; tbl_pinjaman_h lunas jika semua lunas | Tidak generate ID baru |
| 16 | Konfirmasi pembayaran transfer upload bukti | admin.pinjaman.detail-pembayaran | konfirmasiPembayaran POST | tbl_bukti_foto optional; tbl_pengajuan_pembayaran_pinjaman status 4; tempo update | IdGenerator tbl_bukti_foto P+TF+PMB jika upload |
| 17 | Pengajuan pembayaran pinjaman tunai janji temu | nasabah.pinjaman.pembayaran tab janji temu | submitJanjiTemuPembayaran POST | tbl_pengajuan_pembayaran_pinjaman metode_pembayaran tunai; tbl_janji_temu_pembayaran_pinjaman id_pengajuan | IdGenerator P+TN+PMB dan P+TN+JNJT |
| 18 | Konfirmasi pembayaran tunai upload bukti foto | admin.pinjaman.detail-pembayaran | uploadSerahTerima POST | tbl_bukti_foto owner_id pengajuan PMB; tempo update; tbl_pengajuan_pembayaran_pinjaman status 4; tbl_janji_temu_pembayaran_pinjaman status 2 | IdGenerator tbl_bukti_foto P+CS+PMB |

---

## Tabel Bukti Foto (tbl_bukti_foto)

Tabel universal untuk menyimpan file bukti. Kolom: id (string generated), owner_id (ID pemilik misal id pengajuan atau id janji temu), owner_fitur (T Tabungan, P Pinjaman), owner_trans (STR setoran, PNR penarikan, PNCR pencairan, PMB pembayaran, JNJT janji temu), file_path, keterangan. Relasi: owner_id merujuk ke tabel lain tergantung owner_fitur dan owner_trans.

---

## Deskripsi Paragraf per Alur

**Alur 1 Pengajuan setoran tabungan transfer.** View: nasabah tabungan pengajuan transfer. Field input: nominal, keterangan, bukti_foto (satu atau banyak), PIN. Button: submit setoran. Function: Nasabah\TabunganController submitSetoran. Validasi PIN dan minimal satu bukti transfer. Data disimpan ke tbl_pengajuan_tabungan kolom id (generate T+TF+STR), id_anggota, nominal, keterangan, status 1. Setiap file bukti disimpan ke tbl_bukti_foto dengan id generate T+TF+STR, owner_id id pengajuan, owner_fitur T, owner_trans STR, file_path. Relasi: PengajuanTabungan hasMany BuktiFoto via owner_id. Notifikasi admin ke detail pengajuan setor.

**Alur 2 Approval pengajuan setoran tabungan.** View: admin tabungan detail pengajuan setor. Field: optional keterangan. Button: Setujui. Function: Admin\TabunganController approveSetor. Membuat satu baris trans_tabungan dengan id generate T+TF+STR, id_pengajuan_setor, id_anggota, id_jns_via id_jns_transaksi dari master (TF, STR), nominal dari pengajuan, tgl_transaksi now. Update tbl_pengajuan_tabungan status jadi 2. Relasi: trans_tabungan id_pengajuan_setor ke tbl_pengajuan_tabungan.

**Alur 3 Pengajuan setoran tabungan janji temu cash.** View: nasabah tabungan janji temu. Field: nominal, lokasi_temu, tanggal_janji_temu, waktu_janji_temu, keterangan, PIN. Button: submit janji temu. Function: Nasabah\TabunganController submitJanjiTemu. Hanya mengisi tbl_janji_temu_tabungan (tidak ada tbl_pengajuan_tabungan). Kolom: id generate T+CS+JNJT, id_nasabah, lokasi_temu, nominal, tanggal_janji_temu, waktu_janji_temu, keterangan, status 1. Jenis default setoran. Relasi: JanjiTemuTabungan ke jns_lokasi_perusahaan, tbl_nasabah.

**Alur 4 Approval setoran janji temu cash.** View: admin janji temu detail (universal). Field: nominal, keterangan_admin, foto_penerimaan optional. Button: Buat transaksi / proses. Function: Admin\TabunganController createTransFromJanjiTemu. Jika ada file foto disimpan ke tbl_bukti_foto owner_id id janji temu, owner_fitur T, owner_trans JNJT. Update tbl_janji_temu_tabungan status 2 dan keterangan_admin. Insert trans_tabungan dengan id_janji_temu_tabungan, id_pengajuan_setor null, id_anggota, id_jns_via CS, id_jns_transaksi STR, nominal. Generate id trans T+CS+STR.

**Alur 5 Pengajuan penarikan tabungan transfer.** View: nasabah penarikan tabungan, pilih metode transfer. Field: nominal, nama_bank, no_rekening, keterangan, PIN. Button: submit penarikan. Function: Nasabah\TabunganController submitPenarikan. Insert tbl_pengajuan_penarikan_tabungan id generate T+TF+PNR, id_anggota, tgl_pengajuan, nominal, metode_transfer transfer, no_rekening, nama_bank, status 1. Notifikasi admin ke detail pengajuan tarik. Tidak ada janji temu record.

**Alur 6 Approval pengajuan penarikan transfer.** View: admin tabungan detail pengajuan tarik (hanya yang metode transfer). Field: foto_bukti_tf_admin wajib, bank_pengirim. Button: Setujui. Function: Admin\TabunganController approveTarik. Update tbl_pengajuan_penarikan_tabungan status 2 dan foto_bukti_tf_admin path. Insert trans_tabungan id_pengajuan_tarik, id_anggota, id_jns_via TF, id_jns_transaksi PNR, nominal negatif untuk penarikan (logic saldo). Generate id trans T+TF+PNR.

**Alur 7 Pengajuan penarikan tabungan tunai janji temu.** View: nasabah penarikan tabungan, pilih metode tunai. Field: nominal, lokasi_temu, tanggal_janji_temu, waktu_janji_temu, keterangan, PIN. Button: submit. Function: submitPenarikan dengan metode tunai. Insert tbl_pengajuan_penarikan_tabungan id T+TN+PNR, metode_transfer tunai, lokasi_temu, tanggal_janji_temu, waktu_janji_temu, status 1. Insert tbl_janji_temu_tabungan id generate T+CS+JNJT, jenis penarikan, nominal, lokasi, tanggal waktu, status 1. Tidak ada link id antara pengajuan penarikan dan janji temu di tabel (linking saat approval by nominal dan status pending).

**Alur 8 Approval penarikan tunai via janji temu.** Sama dengan createTransFromJanjiTemu untuk jenis penarikan. Cari pengajuan penarikan pending yang match nominal dan id_anggota, update status 2. Insert trans_tabungan id_janji_temu_tabungan, id_pengajuan_tarik (yang ditemukan), id_jns_transaksi PNR, nominal. Update janji temu status 2.

**Alur 9 Pengajuan pinjaman transfer.** View: nasabah pinjaman pengajuan (form transfer). Field: nominal, durasi, keterangan, PIN. Button: Ajukan. Function: Nasabah\PinjamanController submitPengajuanTransfer. Bunga diambil dari master_bunga_pinjaman sesuai durasi. Insert tbl_pengajuan_pinjaman id generate P+TF+PNJ, id_anggota, tgl_pengajuan, nominal, jenis bulanan, durasi, jenis_pencairan transfer, status 1, bunga_persen. Notifikasi admin. Tidak isi tbl_janji_temu_pinjaman.

**Alur 10 Approval pengajuan pinjaman transfer.** View: admin pinjaman detail pengajuan. Field: keterangan_admin optional. Button: Setujui. Function: Admin\PinjamanController approvePengajuan. Ambil master bunga dan denda. Insert tbl_pinjaman_h id P+TF+DPNJM, id_anggota, id_pengajuan, jumlah_pinjam, lama_pinjam, ags_bulan, bunga, bunga_rp, denda_persen, tgl_pinjam, lunas belum. Update tbl_pengajuan_pinjaman status 3 dan bunga_persen. Belum generate tempo angsuran.

**Alur 11 Cairkan pinjaman transfer.** View: admin detail pengajuan (setelah disetujui). Field: tgl_cair, bukti_transfer wajib. Button: Cairkan. Function: cairkanPinjaman. Generate jadwal angsuran ke tempo_pinjaman_b (id P+T+TPNJM sequence). Upload file ke tbl_bukti_foto owner_id id pengajuan, owner_fitur P, owner_trans PNCR. Update tbl_pengajuan_pinjaman status 4 dan tgl_cair. Update tbl_pinjaman_h tgl_pinjam dari input tgl_cair.

**Alur 12 Pengajuan pinjaman janji temu tunai.** View: nasabah pinjaman janji temu. Field: nominal, durasi, lokasi_temu, tanggal_janji_temu, waktu_janji_temu, keterangan, PIN. Button: submit. Function: submitJanjiTemuPinjaman. Insert tbl_pengajuan_pinjaman id P+TN+PNJ, jenis_pencairan tunai, status 1. Insert tbl_janji_temu_pinjaman id P+TN+JNJT, id_pengajuan, id_nasabah, lokasi_temu, nominal, tanggal_janji_temu, waktu_janji_temu, status 1. Notifikasi janji temu. Pengajuan tidak muncul di Pengajuan Terbaru admin, hanya di Janji Temu Universal.

**Alur 13 Approval dan cairkan pinjaman janji temu.** View: admin janji temu detail pinjaman. Field: tgl_cair, keterangan_admin, bukti_transfer optional. Button: Proses / selesai. Function: prosesJanjiTemuPinjaman. Update tbl_janji_temu_pinjaman status 2 dan keterangan_admin. Jika pengajuan status 1 buat tbl_pinjaman_h id P+TN+DPNJM dan update pengajuan status 3. Generate tempo_pinjaman_b. Optional simpan bukti ke tbl_bukti_foto owner_id id janji temu. Update pengajuan status 4 dan tgl_cair.

**Alur 14 Pengajuan pembayaran pinjaman transfer.** View: nasabah pinjaman pembayaran tab transfer. Field: pinjaman_id, tempo_id, nominal, rekening_tujuan, bukti_foto optional, keterangan, PIN. Button: Ajukan pembayaran. Function: submitPembayaranTransfer. Insert tbl_pengajuan_pembayaran_pinjaman id P+TF+PMB, id_anggota, pinjaman_id, tempo_id, jenis_tempo, nominal, rekening_tujuan, status 1. Jika ada file simpan tbl_bukti_foto owner_id id pengajuan pembayaran, owner_fitur P, owner_trans PMB. Relasi: PengajuanPembayaranPinjaman ke tbl_pinjaman_h, tempo_pinjaman_b.

**Alur 15 Approval pembayaran pinjaman transfer.** View: admin detail pembayaran. Button: Setujui pembayaran. Function: approvePembayaran. Hitung denda via hitungDenda. Update tempo_pinjaman_b jumlah_terbayar, denda, status_bayar, tgl_bayar. Jika total lunas update tbl_pinjaman_h lunas. Update tbl_pengajuan_pembayaran_pinjaman status 3 dan tgl_pembayaran. Tidak insert bukti foto di sini (bisa di konfirmasi).

**Alur 16 Konfirmasi pembayaran transfer.** View: admin detail pembayaran (setelah status disetujui, untuk transfer). Field: bukti_transfer optional, keterangan. Button: Konfirmasi pembayaran. Function: konfirmasiPembayaran. Jika upload simpan tbl_bukti_foto. Update angsuran dan pengajuan status 4. Sama seperti approve plus update status terlaksana.

**Alur 17 Pengajuan pembayaran pinjaman tunai janji temu.** View: nasabah pembayaran tab janji temu. Field: pinjaman_id, tempo_id, nominal, lokasi_temu, tanggal_janji_temu, waktu_janji_temu, keterangan, PIN. Button: Ajukan janji temu. Function: submitJanjiTemuPembayaran. Insert tbl_pengajuan_pembayaran_pinjaman id P+TN+PMB, metode_pembayaran tunai, status 1. Insert tbl_janji_temu_pembayaran_pinjaman id P+TN+JNJT, id_pengajuan, lokasi_temu, nominal, tanggal_janji_temu, waktu_janji_temu, status 1. Relasi: JanjiTemuPembayaranPinjaman id_pengajuan ke tbl_pengajuan_pembayaran_pinjaman.

**Alur 18 Konfirmasi pembayaran tunai upload bukti foto.** View: admin detail pembayaran (untuk tunai tampil form upload langsung tanpa harus setujui dulu). Field: foto_serah_terima wajib, keterangan. Button: Upload bukti dan konfirmasi. Function: uploadSerahTerima. Boleh dipanggil saat status 1 atau 3 untuk tunai. Simpan file ke tbl_bukti_foto owner_id id pengajuan pembayaran, owner_fitur P, owner_trans PMB (kode CS). Update tempo_pinjaman_b. Update tbl_pengajuan_pembayaran_pinjaman status 4. Update tbl_janji_temu_pembayaran_pinjaman status 2 jika ada.

---

## Relasi Tabel Penting

tbl_pengajuan_tabungan satu arah ke trans_tabungan via id_pengajuan_setor. tbl_janji_temu_tabungan ke trans_tabungan via id_janji_temu_tabungan. tbl_pengajuan_penarikan_tabungan ke trans_tabungan via id_pengajuan_tarik. tbl_pengajuan_pinjaman ke tbl_pinjaman_h via id_pengajuan. tbl_pinjaman_h ke tempo_pinjaman_b via pinjaman_id. tbl_pengajuan_pembayaran_pinjaman ke tempo_pinjaman_b via tempo_id, ke tbl_pinjaman_h via pinjaman_id. tbl_janji_temu_pinjaman ke tbl_pengajuan_pinjaman via id_pengajuan. tbl_janji_temu_pembayaran_pinjaman ke tbl_pengajuan_pembayaran_pinjaman via id_pengajuan. tbl_bukti_foto owner_id merujuk ke berbagai tabel tergantung owner_fitur dan owner_trans.

---

## Format ID (IdGenerator)

Format: DDMMYYYY + 4 digit sequence + suffix. Suffix = kodeFitur + kodeVia + kodeTrans. Contoh 150220260001PTFPMB artinya 15 Feb 2026, seq 0001, P (Pinjaman) TF (Transfer) PMB (Pembayaran). Setiap kombinasi suffix punya sequence sendiri per tabel.
