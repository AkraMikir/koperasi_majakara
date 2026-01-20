# 📦 Panduan Install Tesseract OCR untuk Windows

## 🎯 Overview

Tesseract OCR diperlukan untuk fitur ekstrak data KTP otomatis di sistem registrasi. Dokumen ini menjelaskan cara install Tesseract OCR di Windows.

---

## ⚠️ Error yang Terjadi

Jika Anda melihat error seperti:
```
Error: Unexpected token '<'
```
atau
```
Tesseract not found
```

Ini berarti Tesseract OCR belum terinstall atau belum dikonfigurasi dengan benar.

---

## 📥 Langkah Install

### **Step 1: Download Tesseract OCR**

1. Buka browser dan kunjungi: https://github.com/UB-Mannheim/tesseract/wiki
2. Download installer untuk Windows:
   - **Recommended**: `tesseract-ocr-w64-setup-5.x.x.exe` (versi terbaru)
   - Atau cari di: https://digi.bib.uni-mannheim.de/tesseract/

### **Step 2: Install Tesseract**

1. Jalankan file installer yang sudah didownload
2. **PENTING**: Pilih lokasi install default:
   ```
   C:\Program Files\Tesseract-OCR\
   ```
3. **PENTING**: Saat instalasi, pastikan pilih **"Additional language data"** dan centang:
   - ✅ **Indonesian (ind)** - WAJIB untuk KTP Indonesia
   - ✅ English (eng) - Opsional tapi recommended
4. Klik "Install" dan tunggu sampai selesai

### **Step 3: Verifikasi Install**

Buka **Command Prompt** atau **PowerShell** dan jalankan:

```powershell
tesseract --version
```

Jika berhasil, akan muncul:
```
tesseract 5.x.x
```

Untuk cek bahasa Indonesia terinstall:
```powershell
tesseract --list-langs
```

Pastikan ada `ind` dalam list:
```
List of available languages:
eng
ind  ← Harus ada ini
osd
```

### **Step 4: Tambahkan ke PATH (Opsional)**

Jika `tesseract --version` tidak berfungsi, tambahkan ke PATH:

1. Buka **System Properties** → **Environment Variables**
2. Edit **Path** di **System Variables**
3. Tambahkan: `C:\Program Files\Tesseract-OCR`
4. Klik OK dan restart terminal

**Atau** tambahkan di file `.env` project:

```env
TESSERACT_PATH=C:\Program Files\Tesseract-OCR\tesseract.exe
```

---

## ✅ Verifikasi di Laravel

Setelah install, test di Laravel:

```bash
php artisan tinker
```

```php
$ocr = app(\App\Services\OcrService::class);
$result = $ocr->extractKtpData('ktp/test-ktp.jpg');
dd($result);
```

---

## 🔧 Troubleshooting

### **Problem 1: "Tesseract not found"**

**Solusi:**
1. Pastikan Tesseract terinstall di `C:\Program Files\Tesseract-OCR\`
2. Tambahkan path di `.env`:
   ```env
   TESSERACT_PATH=C:\Program Files\Tesseract-OCR\tesseract.exe
   ```
3. Restart Laravel server

### **Problem 2: "Language 'ind' not found"**

**Solusi:**
1. Uninstall Tesseract
2. Install ulang dan pastikan pilih **Indonesian language pack**
3. Atau download manual: https://github.com/tesseract-ocr/tessdata
4. Copy `ind.traineddata` ke: `C:\Program Files\Tesseract-OCR\tessdata\`

### **Problem 3: Error "Unexpected token '<'"**

**Solusi:**
Error ini biasanya terjadi karena:
1. Tesseract belum terinstall → Install Tesseract (lihat Step 1-2)
2. Path Tesseract salah → Set `TESSERACT_PATH` di `.env`
3. Language pack tidak ada → Install Indonesian language pack

### **Problem 4: OCR hasil tidak akurat**

**Solusi:**
1. Pastikan foto KTP **jelas dan tidak blur**
2. Pastikan foto KTP **tidak terpotong**
3. Pastikan **cahaya cukup** saat foto
4. Coba crop foto agar hanya KTP saja (tanpa background)

---

## 📝 Catatan Penting

1. **Lokasi Install**: 
   - Default: `C:\Program Files\Tesseract-OCR\`
   - Jangan ubah lokasi install jika tidak perlu

2. **Language Pack**:
   - **WAJIB** install Indonesian (`ind`) untuk KTP
   - File: `C:\Program Files\Tesseract-OCR\tessdata\ind.traineddata`

3. **Version**:
   - Recommended: Tesseract 5.x (terbaru)
   - Support Windows 10/11

4. **Laravel Package**:
   - Package `thiagoalessio/tesseract_ocr` sudah terinstall
   - Tidak perlu install package tambahan

---

## 🚀 Setelah Install

Setelah Tesseract terinstall:

1. **Restart Laravel server**:
   ```bash
   php artisan serve
   ```

2. **Test di browser**:
   - Buka halaman registrasi
   - Upload foto KTP
   - Klik "Proses OCR"
   - Data KTP akan otomatis terisi

---

## 📞 Support

Jika masih ada masalah:
1. Cek log Laravel: `storage/logs/laravel.log`
2. Cek error di browser console (F12)
3. Pastikan Tesseract terinstall dengan benar

---

**Terakhir diperbarui**: 2025
