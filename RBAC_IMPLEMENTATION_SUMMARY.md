# Role-Based Access Control (RBAC) Implementation Summary

## Overview
Implementasi sistem pembatasan akses untuk memisahkan hak akses antara **Admin Utama** dan **Admin Operasional** di sistem Koperasi Majakara.

## Tanggal Implementasi
17 Februari 2026

## 🎯 Tujuan
- Memberikan akses penuh kepada Admin Utama untuk semua fitur
- Membatasi akses Admin Operasional untuk fitur-fitur tertentu (CRUD manual)
- Meningkatkan keamanan sistem dengan authentication middleware
- Memberikan user experience yang baik dengan UI conditional rendering

## 📊 Matrix Hak Akses

| Fitur | Admin Utama | Admin Operasional |
|-------|-------------|-------------------|
| **Dashboard** | ✅ Full | ✅ Full |
| **Tabungan - Approval** | ✅ Yes | ✅ Yes |
| **Tabungan - CRUD Transaksi** | ✅ Yes | ❌ No |
| **Pinjaman - Approval & Cairkan** | ✅ Yes | ✅ Yes |
| **Pinjaman - CRUD Manual** | ✅ Yes | ❌ No |
| **Pinjaman - Pelunasan Dipercepat** | ✅ Yes | ❌ No |
| **Master Data - View** | ✅ Yes | ✅ Yes |
| **Master Data - CRUD** | ✅ Yes | ❌ No |
| **Laporan Keuangan** | ✅ Full | ✅ Full |
| **Nasabah - View** | ✅ Yes | ✅ Yes |
| **Nasabah - Approve Perubahan** | ✅ Yes | ❌ No |
| **Nasabah - Reset PIN** | ✅ Yes | ❌ No |
| **Janji Temu Universal** | ✅ Full | ✅ Full |
| **Notifikasi** | ✅ Full | ✅ Full |

## 🏗️ Komponen yang Diimplementasikan

### 1. Middleware Layer
**Lokasi:** `app/Http/Middleware/`

#### AdminMiddleware.php
- Validasi user sudah login
- Cek role user adalah admin (admin_utama atau admin_operasional)
- Redirect jika bukan admin

#### AdminUtamaMiddleware.php
- Restrict akses hanya untuk admin_utama
- Return 403 jika admin_operasional mencoba akses

#### CheckAdminPermission.php
- Granular permission checking per action
- Support parameter: crud-tabungan, crud-pinjaman, crud-master-data, manage-nasabah, pelunasan-dipercepat

### 2. Permission Service
**Lokasi:** `app/Services/AdminPermissionService.php`

Service class untuk centralized permission logic dengan method-method:
- `isAdminUtama($user)` - Check if Admin Utama
- `isAdminOperasional($user)` - Check if Admin Operasional
- `canCrudTabunganTransaksi($user)` - Check CRUD tabungan permission
- `canCrudPinjamanAktif($user)` - Check CRUD pinjaman permission
- `canCrudMasterData($user)` - Check master data CRUD permission
- `canManageNasabah($user)` - Check nasabah management permission
- `canPelunasanDipercepat($user)` - Check pelunasan dipercepat permission
- `getRoleDisplayName($user)` - Get role display name
- `getRoleBadgeColor($user)` - Get role badge color for UI

### 3. Route Protection
**Lokasi:** `routes/web.php`

#### Perubahan Utama:
1. **Authentication Middleware** - Semua admin routes sekarang protected dengan `auth` dan `admin` middleware
2. **Granular Protection** - Route-route CRUD diberi middleware tambahan:
   - `admin.permission:crud-tabungan` untuk CRUD transaksi tabungan
   - `admin.permission:crud-pinjaman` untuk CRUD pinjaman manual
   - `admin.permission:crud-master-data` untuk semua CRUD master data
   - `admin.permission:manage-nasabah` untuk approve/reject/reset PIN nasabah
   - `admin.permission:pelunasan-dipercepat` untuk pelunasan dipercepat

### 4. Middleware Registration
**Lokasi:** `bootstrap/app.php`

Middleware aliases didaftarkan:
```php
'admin' => \App\Http\Middleware\AdminMiddleware::class,
'admin.utama' => \App\Http\Middleware\AdminUtamaMiddleware::class,
'admin.permission' => \App\Http\Middleware\CheckAdminPermission::class,
```

### 5. Blade Directives
**Lokasi:** `app/Providers/AppServiceProvider.php`

Custom Blade directives untuk conditional rendering:
- `@isAdminUtama` - Check if current user is Admin Utama
- `@isAdminOperasional` - Check if current user is Admin Operasional
- `@isAdmin` - Check if current user is any admin
- `@canCrudTabungan` - Check if can CRUD tabungan
- `@canCrudPinjaman` - Check if can CRUD pinjaman
- `@canPelunasanDipercepat` - Check if can pelunasan dipercepat
- `@canCrudMasterData` - Check if can CRUD master data
- `@canManageNasabah` - Check if can manage nasabah

### 6. Controller Authorization
Authorization checks ditambahkan di controller methods:

#### TabunganController
- `createTransaksi()` - Check CRUD permission
- `storeTransaksi()` - Check CRUD permission
- `editTransaksi()` - Check CRUD permission
- `updateTransaksi()` - Check CRUD permission
- `destroyTransaksi()` - Check CRUD permission
- `editPengajuanSetor()` - Check CRUD permission
- `deletePengajuanSetor()` - Check CRUD permission

#### PinjamanController
- `createPinjaman()` - Check CRUD permission
- `storePinjaman()` - Check CRUD permission
- `editPinjaman()` - Check CRUD permission
- `updatePinjaman()` - Check CRUD permission
- `deletePinjaman()` - Check CRUD permission
- `pelunasanDipercepat()` - Check pelunasan permission

#### MasterDataController
- Semua method CRUD (Create, Store, Edit, Update, Destroy, ToggleStatus) untuk:
  - Bunga Pinjaman
  - Denda Pinjaman
  - Suku Bunga Tabungan
  - Tenor Deposito
  - Suku Bunga Deposito
  - Barang Gadai
  - Lokasi Perusahaan
  - Jenis Deposito
  - Biaya Transfer

#### NasabahManagementController
- `approveChange()` - Check manage permission
- `rejectChange()` - Check manage permission
- `resetPin()` - Check manage permission
- `generateRandomPin()` - Check manage permission

### 7. View Updates
Conditional rendering ditambahkan di views:

#### Admin Header
- **Lokasi:** `resources/views/components/admin/header.blade.php`
- Menampilkan role badge (Admin Utama / Admin Operasional)

#### Tabungan Views
- **Lokasi:** `resources/views/admin/tabungan/transaksi.blade.php`
- Tombol "Buat Transaksi Manual" hanya ditampilkan untuk Admin Utama
- Tombol Edit/Hapus transaksi hanya ditampilkan untuk Admin Utama

#### Pinjaman Views
- **Lokasi:** `resources/views/admin/pinjaman/pinjaman-aktif.blade.php`
- Tombol "Tambah Pinjaman" hanya ditampilkan untuk Admin Utama

#### Master Data Views
- **Lokasi:** `resources/views/admin/master-data/bunga-pinjaman/index.blade.php`
- Tombol "Tambah Data" hanya ditampilkan untuk Admin Utama
- Tombol Edit/Hapus/Toggle Status hanya ditampilkan untuk Admin Utama
- Admin Operasional hanya bisa view (read-only)

#### Nasabah Management Views
- **Lokasi:** `resources/views/admin/nasabah/detail.blade.php`
- Section "Reset PIN Nasabah" hanya ditampilkan untuk Admin Utama

## 🔒 Security Enhancements

### Critical Security Fix
**URGENT FIX YANG SUDAH DIIMPLEMENTASIKAN:**
- Semua admin routes sekarang protected dengan authentication middleware
- Sebelumnya admin routes TIDAK memiliki middleware auth (vulnerability)

### Triple Layer Protection
1. **Route Middleware** - First line of defense (middleware di routes)
2. **Controller Authorization** - Second layer (authorization checks di controller)
3. **UI Conditional Rendering** - User experience (hide buttons yang tidak bisa diakses)

## 📝 Testing Checklist

### Testing sebagai Admin Utama
✅ Dapat mengakses semua fitur
✅ Dapat membuat/edit/hapus transaksi tabungan manual
✅ Dapat membuat/edit/hapus pinjaman manual
✅ Dapat CRUD semua master data
✅ Dapat approve/reject perubahan nasabah
✅ Dapat reset PIN nasabah
✅ Dapat melakukan pelunasan dipercepat

### Testing sebagai Admin Operasional
✅ Dapat view dashboard
✅ Dapat approve/reject tabungan setor/tarik
✅ TIDAK dapat CRUD transaksi tabungan manual (tombol tidak muncul)
✅ Dapat approve/reject/cairkan pinjaman
✅ TIDAK dapat CRUD pinjaman manual (tombol tidak muncul)
✅ TIDAK dapat pelunasan dipercepat
✅ Dapat view master data (read-only)
✅ TIDAK dapat CRUD master data (tombol tidak muncul, status hanya display)
✅ Dapat view & export laporan
✅ Dapat view nasabah list & detail
✅ TIDAK dapat approve/reject perubahan nasabah
✅ TIDAK dapat reset PIN nasabah (section tidak muncul)

### Direct URL Access Testing
Test admin operasional mencoba akses restricted URLs:
- `/admin/tabungan/transaksi/create` → Return 403 Forbidden
- `/admin/pinjaman/pinjaman-aktif/create` → Return 403 Forbidden
- `/admin/master-data/bunga-pinjaman/create` → Return 403 Forbidden
- `/admin/nasabah/123/reset-pin` → Return 403 Forbidden

## 🤖 Automated Testing (PHPUnit)

Tes otomatis untuk RBAC tersedia di **`tests/Feature/RbacAdminAccessTest.php`**.

### Menjalankan tes RBAC
```bash
php artisan test tests/Feature/RbacAdminAccessTest.php
```

### Skenario yang dites (27 test cases)
- **Unauthenticated:** redirect ke login saat akses admin
- **Nasabah:** dapat 403 saat akses admin dashboard / tabungan
- **Admin Operasional – dilarang (403):** create transaksi tabungan, create pinjaman, create master data bunga pinjaman, reset PIN nasabah, edit transaksi, edit pinjaman, generate random PIN
- **Admin Operasional – diizinkan (200):** dashboard, tabungan index/transaksi list, pinjaman index/pinjaman aktif, master data index & view, laporan, nasabah index, notifications
- **Admin Utama – full access (200):** dashboard, tabungan create transaksi, pinjaman create, master data create, nasabah index, generate random PIN

### Catatan
- Tes memakai `RefreshDatabase` dan SQLite in-memory.
- Migrasi view `v_janji_temu_universal` di-skip saat driver SQLite agar kompatibel dengan testing.

---

## 🚀 Cara Testing Manual

### 1. Login sebagai Admin Utama
```
Email: admin.utama@koperasi.com
Password: password123
PIN: 123456
```

### 2. Login sebagai Admin Operasional
```
Email : admin.operasional1@koperasi.com
Password: password123
PIN: 567890
```

### 3. Verifikasi Akses
- Browse ke setiap menu
- Perhatikan tombol yang muncul/tidak muncul
- Coba akses URL restricted langsung via browser
- Verifikasi error 403 muncul untuk admin operasional

## 📚 Documentation for Developers

### Menggunakan Permission Service di Controller
```php
use App\Services\AdminPermissionService;

public function someMethod()
{
    $permissionService = app(AdminPermissionService::class);
    
    if (!$permissionService->canCrudTabunganTransaksi(auth()->user())) {
        abort(403, 'Anda tidak memiliki akses untuk fitur ini.');
    }
    
    // ... rest of code
}
```

### Menggunakan Blade Directives di View
```blade
@canCrudTabungan
    <a href="{{ route('admin.tabungan.create-transaksi') }}">
        Buat Transaksi Manual
    </a>
@endcanCrudTabungan

@isAdminUtama
    <button>Admin Utama Only Button</button>
@endisAdminUtama
```

### Menambah Route dengan Middleware
```php
// Basic admin authentication
Route::middleware(['auth', 'admin'])->group(function () {
    // Routes accessible by all admins
});

// Admin Utama only
Route::middleware(['auth', 'admin.utama'])->group(function () {
    // Routes only for Admin Utama
});

// Granular permission
Route::middleware(['auth', 'admin.permission:crud-tabungan'])->group(function () {
    // Routes with specific permission check
});
```

## ⚠️ Important Notes

1. **Backward Compatibility** - Existing admin utama accounts retain full access
2. **User Seeder** - Admin accounts sudah dibuat di database seeder
3. **Future Scalability** - Permission service mudah di-extend untuk role tambahan
4. **Clean UI** - Admin operasional tidak melihat tombol yang tidak bisa mereka akses
5. **Security First** - Critical security fix untuk authentication middleware sudah diimplementasikan

## 🔄 Future Enhancements (Optional)

1. **Database Permissions Table** - Store custom permissions per admin
2. **Audit Logging** - Track semua admin actions untuk compliance
3. **2FA Authentication** - Two-factor authentication untuk admin
4. **Rate Limiting** - Rate limiting untuk sensitive actions
5. **Role Management UI** - Interface untuk manage roles & permissions
6. **Permission Groups** - Group permissions untuk management yang lebih mudah

## 📞 Support

Untuk pertanyaan atau issues terkait RBAC implementation:
- Check dokumentasi di file ini
- Review code di komponen yang disebutkan di atas
- Test dengan user accounts yang ada di UserSeeder

---

**Status:** ✅ COMPLETED
**Version:** 1.0
**Last Updated:** 17 Februari 2026
