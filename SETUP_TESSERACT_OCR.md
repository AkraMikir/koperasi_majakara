# Setup Tesseract OCR untuk Windows

## 📋 Instalasi Tesseract OCR

Package `thiagoalessio/tesseract_ocr` yang sudah diinstall via Composer hanya merupakan **wrapper/interface** untuk Tesseract OCR. Anda perlu menginstall **Tesseract OCR binary** terpisah di sistem Windows.

---

## 🚀 Cara Install Tesseract OCR di Windows

### **Opsi 1: Install via Chocolatey (Recommended)**

1. **Install Chocolatey** (jika belum ada):
   ```powershell
   # Buka PowerShell sebagai Administrator
   Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))
   ```

2. **Install Tesseract OCR**:
   ```powershell
   choco install tesseract
   ```

3. **Install Language Pack Indonesia**:
   ```powershell
   choco install tesseract-lang-ind
   ```

4. **Verifikasi Installasi**:
   ```powershell
   tesseract --version
   ```
   
   Output yang diharapkan:
   ```
   tesseract 5.x.x
    leptonica-1.x.x
    ...
   ```

---

### **Opsi 2: Install Manual (Download Installer)**

1. **Download Tesseract OCR**:
   - Kunjungi: https://github.com/UB-Mannheim/tesseract/wiki
   - Download installer untuk Windows (misal: `tesseract-ocr-w64-setup-5.x.x.exe`)

2. **Install Tesseract**:
   - Jalankan installer yang sudah didownload
   - **PENTING**: Saat install, pilih opsi **"Add to PATH"** atau centang checkbox untuk menambahkan ke PATH
   - Install ke lokasi default: `C:\Program Files\Tesseract-OCR\`

3. **Download Language Pack Indonesia**:
   - Download file `ind.traineddata` dari: https://github.com/tesseract-ocr/tessdata
   - Copy file ke folder: `C:\Program Files\Tesseract-OCR\tessdata\`

4. **Verifikasi Installasi**:
   ```powershell
   tesseract --version
   ```

---

### **Opsi 3: Install via Scoop**

1. **Install Scoop** (jika belum ada):
   ```powershell
   Set-ExecutionPolicy RemoteSigned -Scope CurrentUser
   irm get.scoop.sh | iex
   ```

2. **Install Tesseract**:
   ```powershell
   scoop install tesseract
   ```

---

## ⚙️ Konfigurasi (Jika Tesseract Tidak di PATH)

Jika Tesseract tidak terdeteksi otomatis, tambahkan path ke file `.env`:

```env
# Path ke Tesseract executable (jika tidak di PATH)
TESSERACT_PATH=C:\Program Files\Tesseract-OCR\tesseract.exe
```

**Catatan**: Jika Tesseract sudah di PATH, tidak perlu menambahkan `TESSERACT_PATH` di `.env`.

---

## ✅ Test Installasi

Setelah install, test dengan command:

```powershell
# Test Tesseract
tesseract --version

# Test dengan bahasa Indonesia
tesseract --list-langs
```

Pastikan output `--list-langs` menampilkan `ind` (Indonesian).

---

## 🔧 Troubleshooting

### **Error: "tesseract is not recognized"**

**Solusi 1**: Tambahkan Tesseract ke PATH
1. Buka **System Properties** → **Environment Variables**
2. Edit **Path** variable
3. Tambahkan: `C:\Program Files\Tesseract-OCR`
4. Restart terminal/PowerShell

**Solusi 2**: Set path di `.env`
```env
TESSERACT_PATH=C:\Program Files\Tesseract-OCR\tesseract.exe
```

### **Error: "Language 'ind' not found"**

**Solusi**: Download dan install language pack Indonesia
1. Download `ind.traineddata` dari: https://github.com/tesseract-ocr/tessdata
2. Copy ke: `C:\Program Files\Tesseract-OCR\tessdata\ind.traineddata`

### **Error: "Permission denied"**

**Solusi**: Jalankan PowerShell/Command Prompt sebagai Administrator

---

## 📝 Verifikasi di Laravel

Setelah install, test OCR di Laravel:

1. **Buka Tinker**:
   ```powershell
   php artisan tinker
   ```

2. **Test OCR Service**:
   ```php
   $ocr = app(\App\Services\OcrService::class);
   // Test dengan path gambar KTP
   $result = $ocr->extractKtpData('ktp/test-ktp.jpg');
   dd($result);
   ```

---

## 🎯 Langkah Selanjutnya

Setelah Tesseract OCR terinstall:

1. ✅ Test OCR dengan upload foto KTP di form registrasi
2. ✅ Pastikan language pack Indonesia (`ind`) terinstall
3. ✅ Jika ada error, cek log di `storage/logs/laravel.log`

---

## 📚 Referensi

- **Tesseract OCR Official**: https://github.com/tesseract-ocr/tesseract
- **Windows Installer**: https://github.com/UB-Mannheim/tesseract/wiki
- **Language Packs**: https://github.com/tesseract-ocr/tessdata
- **Laravel Package**: https://github.com/thiagoalessio/tesseract-ocr-for-php

---

## ⚠️ Catatan Penting

1. **Restart Terminal**: Setelah install, tutup dan buka kembali terminal/PowerShell
2. **Restart Web Server**: Jika menggunakan XAMPP/WAMP, restart Apache
3. **Language Pack**: Pastikan `ind.traineddata` terinstall untuk support bahasa Indonesia
4. **Path**: Jika Tesseract tidak di PATH, set `TESSERACT_PATH` di `.env`
