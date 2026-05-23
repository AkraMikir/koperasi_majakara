# =============================================================================
# REFINED SCAN - Teks Bahasa Inggris yang HARUS Diterjemahkan
# Koperasi Majakara - Hanya mendeteksi teks UI yang benar-benar Inggris
# Mengecualikan kata serapan yang sudah lazim di Indonesia
# =============================================================================

$viewsPath  = "D:\project\koperasi_majakara\resources\views"
$outputFile = "D:\project\koperasi_majakara\english_refined_report.md"

# =========================================================================
# POLA YANG HARUS DITERJEMAHKAN (bukan serapan / tidak lazim di Indonesia)
# =========================================================================
$mustTranslate = [ordered]@{

    # --- Waktu (Carbon diffForHumans) ---
    "days ago"       = "hari yang lalu"
    "hours ago"      = "jam yang lalu"
    "minutes ago"    = "menit yang lalu"
    "seconds ago"    = "detik yang lalu"
    "day ago"        = "hari yang lalu"
    "hour ago"       = "jam yang lalu"
    "minute ago"     = "menit yang lalu"
    "second ago"     = "detik yang lalu"
    "just now"       = "baru saja"
    "ago"            = "yang lalu"

    # --- Status Badge (hardcoded di blade) ---
    "ACTIVE"         = "AKTIF"
    "GRACE PERIOD"   = "MASA TENGGANG"
    "Auctioned"      = "Dilelang"

    # --- Tombol / Aksi ---
    "Submit"         = "Kirim"
    "Upload"         = "Unggah"
    "Approve"        = "Setujui"
    "Reject"         = "Tolak"
    "Export"         = "Ekspor"
    "Import"         = "Impor"
    "Print"          = "Cetak"

    # --- Peringatan / Konfirmasi ---
    "Are you sure"   = "Apakah Anda yakin"
    "Do you want"    = "Apakah Anda ingin"
    "Please enter"   = "Silakan masukkan"
    "Please select"  = "Silakan pilih"
    "Please fill"    = "Silakan isi"
    "Please wait"    = "Mohon tunggu"
    "Try again"      = "Coba lagi"
    "Something went wrong" = "Terjadi kesalahan"
    "Access denied"  = "Akses ditolak"
    "Permission denied" = "Izin ditolak"
    "Not found"      = "Tidak ditemukan"
    "No data"        = "Tidak ada data"

    # --- Pesan Sistem ---
    "Successfully"   = "Berhasil"
    "Failed to"      = "Gagal"
    "Unable to"      = "Tidak dapat"
    "Loading"        = "Memuat"
    "Processing"     = "Memproses"
    "Powered by"     = "Didukung oleh"

    # --- Label UI Spesifik ---
    "Internal note"  = "Catatan internal"
    "Actions"        = "Aksi"
    "Log in"         = "Masuk"
    "Sign in"        = "Masuk"
    "Sign up"        = "Daftar"
    "Sign out"       = "Keluar"
    "Log out"        = "Keluar"
    "Forgot password" = "Lupa kata sandi"
    "Remember me"    = "Ingat saya"
    "All rights reserved" = "Hak cipta dilindungi"
    "Coming soon"    = "Segera hadir"
    "Read more"      = "Baca selengkapnya"
    "View all"       = "Lihat semua"
    "Load more"      = "Muat lebih banyak"
    "Show all"       = "Tampilkan semua"
    "Click here"     = "Klik di sini"
    "No data available" = "Tidak ada data tersedia"
    "No record"      = "Tidak ada catatan"
    "No result"      = "Tidak ada hasil"
    "Page not found" = "Halaman tidak ditemukan"
    "Contact us"     = "Hubungi kami"
    "About us"       = "Tentang kami"

    # --- Teks Pengguna Akhir Spesifik di Proyek Ini ---
    "Perhatian Sebelum Submit" = "Perhatian Sebelum Mengirim"
    "Total Request"  = "Total Permintaan"
    "Sudah Dilelang \(Auctioned\)" = "Sudah Dilelang"
    "Terakhir Update" = "Terakhir Diperbarui"
    "via Dashboard"  = "via Dasbor"
    "Kembali ke Dashboard" = "Kembali ke Dasbor"
}

# =========================================================================
Write-Host ""
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "   REFINED SCAN - Teks INGGRIS yang Wajib Diterjemahkan" -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host " Target Folder : $viewsPath" -ForegroundColor Yellow
Write-Host " Output Report : $outputFile" -ForegroundColor Yellow
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""

if (-not (Test-Path $viewsPath)) {
    Write-Host "[ERROR] Folder views tidak ditemukan!" -ForegroundColor Red
    exit 1
}

$bladeFiles  = Get-ChildItem -Path $viewsPath -Recurse -Filter "*.blade.php"
Write-Host "[INFO] Total file .blade.php ditemukan: $($bladeFiles.Count)" -ForegroundColor Green
Write-Host ""

$results      = [ordered]@{}
$totalMatches = 0
$totalFiles   = 0

foreach ($file in $bladeFiles) {
    $relativePath = $file.FullName.Replace($viewsPath + "\", "")
    $lines        = Get-Content $file.FullName -Encoding UTF8
    $fileMatches  = @()

    for ($i = 0; $i -lt $lines.Count; $i++) {
        $line    = $lines[$i]
        $lineNum = $i + 1

        # Skip baris komentar PHP murni
        $trimmed = $line.Trim()
        if ($trimmed -match '^//' -or $trimmed -match '^/\*' -or $trimmed -match '^\*') { continue }

        foreach ($pattern in $mustTranslate.Keys) {
            if ($line -match [regex]::Escape($pattern) -or ($pattern -match '\\' -and $line -match $pattern)) {
                $terjemahan   = $mustTranslate[$pattern]
                $matchedWord  = ([regex]::Match($line, [regex]::Escape($pattern))).Value
                if (-not $matchedWord -and $pattern -match '\\') {
                    $matchedWord = ([regex]::Match($line, $pattern)).Value
                }

                $fileMatches += [PSCustomObject]@{
                    Line        = $lineNum
                    EnglishText = $matchedWord
                    Terjemahan  = $terjemahan
                    Content     = $line.Trim()
                }
                $totalMatches++
                break
            }
        }
    }

    if ($fileMatches.Count -gt 0) {
        $results[$relativePath] = $fileMatches
        $totalFiles++
    }
}

# =========================================================================
# Tulis Laporan Markdown
# =========================================================================
$reportLines = @()
$reportLines += "# Laporan Refined: Teks Bahasa Inggris yang Wajib Diterjemahkan"
$reportLines += ""
$reportLines += "> **Tanggal Scan**: $(Get-Date -Format 'dd MMMM yyyy HH:mm')"
$reportLines += "> **Total File Blade**: $($bladeFiles.Count)"
$reportLines += "> **File yang Perlu Diterjemahkan**: $totalFiles"
$reportLines += "> **Total Teks yang Perlu Diterjemahkan**: $totalMatches"
$reportLines += ""
$reportLines += "---"
$reportLines += ""

foreach ($filePath in $results.Keys) {
    $matches = $results[$filePath]
    $reportLines += "## 📄 ``$filePath``"
    $reportLines += ""
    $reportLines += "| Baris | Teks Inggris | Terjemahan | Isi Baris |"
    $reportLines += "|-------|--------------|------------|-----------|"
    foreach ($m in $matches) {
        $safeContent = $m.Content -replace '\|', '\|'
        $reportLines += "| $($m.Line) | ``$($m.EnglishText)`` | **$($m.Terjemahan)** | $safeContent |"
    }
    $reportLines += ""
}

$reportLines += "---"
$reportLines += ""
$reportLines += "## Ringkasan per Folder"
$reportLines += ""
$folderGroups = $results.Keys | Group-Object { ($_ -split '\\')[0] } | Sort-Object Name
foreach ($group in $folderGroups) {
    $folderTotal = ($results.Keys | Where-Object { $_ -like "$($group.Name)*" }).Count
    $reportLines += "- **$($group.Name)/**: $folderTotal file"
}

$reportLines | Out-File -FilePath $outputFile -Encoding UTF8

Write-Host "[SELESAI] Laporan tersimpan ke: $outputFile" -ForegroundColor Green
Write-Host ""
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host " Ringkasan:" -ForegroundColor Cyan
Write-Host "   - File yang perlu terjemahan : $totalFiles" -ForegroundColor Yellow
Write-Host "   - Total teks perlu terjemahan : $totalMatches" -ForegroundColor Yellow
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""

foreach ($group in $folderGroups) {
    Write-Host "   [$($group.Name)/]" -ForegroundColor Magenta
    foreach ($f in ($results.Keys | Where-Object { $_ -like "$($group.Name)*" } | Sort-Object)) {
        Write-Host "     - $f ($($results[$f].Count) teks)" -ForegroundColor Gray
    }
}
Write-Host ""
