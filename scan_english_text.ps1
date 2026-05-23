# =============================================================================
# SCAN ENGLISH TEXT IN BLADE VIEWS
# Koperasi Majakara - Language Standardization Tool
# =============================================================================
# Jalankan dari root project: .\scan_english_text.ps1
# =============================================================================

$viewsPath = "D:\project\koperasi_majakara\resources\views"
$outputFile = "D:\project\koperasi_majakara\english_text_report.md"

# -------------------------------------------------------------------------
# Daftar kata/frasa bahasa Inggris yang umum ditemukan di UI
# -------------------------------------------------------------------------
$englishPatterns = @(

    # --- Navigasi & Umum ---
    '\bDashboard\b',
    '\bHome\b',
    '\bProfile\b',
    '\bSettings\b',
    '\bLogout\b',
    '\bLogin\b',
    '\bRegister\b',
    '\bBack\b',
    '\bNext\b',
    '\bPrevious\b',
    '\bClose\b',
    '\bCancel\b',
    '\bSearch\b',
    '\bFilter\b',
    '\bSort\b',
    '\bMenu\b',
    '\bNavigation\b',

    # --- Aksi / Tombol ---
    '\bSubmit\b',
    '\bSave\b',
    '\bUpdate\b',
    '\bDelete\b',
    '\bEdit\b',
    '\bAdd\b',
    '\bCreate\b',
    '\bConfirm\b',
    '\bApprove\b',
    '\bReject\b',
    '\bDownload\b',
    '\bUpload\b',
    '\bPrint\b',
    '\bExport\b',
    '\bImport\b',
    '\bView\b',
    '\bDetail[s]?\b',
    '\bShow\b',
    '\bHide\b',
    '\bReset\b',
    '\bClear\b',
    '\bRemove\b',
    '\bChange\b',
    '\bChoose\b',
    '\bSelect\b',
    '\bApply\b',
    '\bProcess\b',
    '\bVerify\b',
    '\bActivate\b',

    # --- Status ---
    '\bPending\b',
    '\bActive\b',
    '\bInactive\b',
    '\bApproved\b',
    '\bRejected\b',
    '\bCompleted\b',
    '\bCancelled\b',
    '\bExpired\b',
    '\bFailed\b',
    '\bSuccess\b',
    '\bError\b',
    '\bWarning\b',
    '\bLoading\b',
    '\bProcessing\b',
    '\bOn Progress\b',

    # --- Tabel / Data ---
    '\bNo\.\b',
    '\bAction[s]?\b',
    '\bTotal\b',
    '\bDate\b',
    '\bName\b',
    '\bType\b',
    '\bAmount\b',
    '\bBalance\b',
    '\bStatus\b',
    '\bDescription\b',
    '\bNote[s]?\b',
    '\bPeriod\b',
    '\bDuration\b',
    '\bInterest\b',
    '\bProfit\b',
    '\bPayment\b',
    '\bInstallment\b',
    '\bDue Date\b',
    '\bStart Date\b',
    '\bEnd Date\b',
    '\bCreated At\b',
    '\bUpdated At\b',
    '\bLast Updated\b',

    # --- Form ---
    '\bEmail\b',
    '\bPassword\b',
    '\bUsername\b',
    '\bPhone\b',
    '\bAddress\b',
    '\bCity\b',
    '\bCountry\b',
    '\bProvince\b',
    '\bZip Code\b',
    '\bBirth Date\b',
    '\bGender\b',
    '\bOccupation\b',
    '\bIncome\b',
    '\bAccount Number\b',
    '\bAccount Name\b',
    '\bAccount Type\b',

    # --- Pesan / Alert ---
    'Are you sure',
    'Do you want',
    'Please enter',
    'Please select',
    'Please fill',
    'This field is required',
    'Invalid',
    'Not found',
    'No data',
    'No record',
    'No result',
    'Something went wrong',
    'Try again',
    'Please wait',
    'Successfully',
    'Failed to',
    'Unable to',
    'Access denied',
    'Permission denied',
    'Page not found',
    'Data not found',
    'Record not found',

    # --- Keuangan/Produk ---
    '\bLoan\b',
    '\bDeposit\b',
    '\bSaving[s]?\b',
    '\bWithdrawal\b',
    '\bTransfer\b',
    '\bTransaction[s]?\b',
    '\bMember\b',
    '\bCustomer\b',
    '\bClient\b',
    '\bAccount\b',
    '\bBranch\b',
    '\bReport\b',
    '\bStatement\b',
    '\bReceipt\b',
    '\bInvoice\b',
    '\bPayment History\b',
    '\bLoan Application\b',
    '\bCredit\b',
    '\bDebit\b',
    '\bOverdue\b',
    '\bPenalty\b',
    '\bFine\b',
    '\bFee\b',
    '\bCharge\b',
    '\bRate\b',
    '\bTerm[s]?\b',
    '\bCollateral\b',
    '\bGuarantor\b',

    # --- Waktu ---
    '\bMonth[s]?\b',
    '\bYear[s]?\b',
    '\bDay[s]?\b',
    '\bWeek[s]?\b',
    '\bToday\b',
    '\bYesterday\b',
    '\bTomorrow\b',
    '\bAnnual\b',
    '\bMonthly\b',
    '\bWeekly\b',
    '\bDaily\b',
    '\bQuarterly\b',

    # --- Kalimat UI lainnya ---
    'Welcome',
    'Hello',
    'Hi\b',
    'Good morning',
    'Good afternoon',
    'Good evening',
    'Thank you',
    'Please',
    'Click here',
    'Read more',
    'See more',
    'View all',
    'Show all',
    'Load more',
    'No data available',
    'Data is empty',
    'Coming soon',
    'Under construction',
    'Powered by',
    'All rights reserved',
    'Contact us',
    'About us',
    'Terms and conditions',
    'Privacy policy',
    'Forgot password',
    'Remember me',
    'Sign in',
    'Sign up',
    'Sign out',
    'Log in',
    'Log out'
)

# -------------------------------------------------------------------------
# Mulai proses scanning
# -------------------------------------------------------------------------
Write-Host ""
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "   SCAN TEKS BAHASA INGGRIS - KOPERASI MAJAKARA" -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host " Target Folder : $viewsPath" -ForegroundColor Yellow
Write-Host " Output Report : $outputFile" -ForegroundColor Yellow
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""

# Validasi folder
if (-not (Test-Path $viewsPath)) {
    Write-Host "[ERROR] Folder views tidak ditemukan: $viewsPath" -ForegroundColor Red
    exit 1
}

# Ambil semua file .blade.php secara rekursif
$bladeFiles = Get-ChildItem -Path $viewsPath -Recurse -Filter "*.blade.php"
Write-Host "[INFO] Total file .blade.php ditemukan: $($bladeFiles.Count)" -ForegroundColor Green
Write-Host ""

$results = @{}
$totalMatches = 0
$totalFiles = 0

foreach ($file in $bladeFiles) {
    $relativePath = $file.FullName.Replace($viewsPath + "\", "")
    $lines = Get-Content $file.FullName -Encoding UTF8
    $fileMatches = @()

    for ($i = 0; $i -lt $lines.Count; $i++) {
        $line = $lines[$i]
        $lineNum = $i + 1

        # Skip baris yang hanya komentar PHP/Blade (tidak ada teks user-facing)
        $trimmed = $line.Trim()
        if ($trimmed -match '^\{\{--' -or $trimmed -match '^//' -or $trimmed -match '^#' -or $trimmed -match '^/\*' -or $trimmed -match '^\*') {
            continue
        }

        foreach ($pattern in $englishPatterns) {
            if ($line -match $pattern) {
                # Cek apakah match ada dalam konteks teks yang tampil (bukan hanya dalam variabel PHP/route/class)
                # Kita tetap tampilkan tapi beri tanda jika dalam string PHP murni
                $context = "UI"
                if ($line -match "^\s*@php|^\s*\$[a-zA-Z]|^\s*//|^\s*/\*") {
                    $context = "PHP"
                }

                $matchedWord = ([regex]::Match($line, $pattern)).Value
                $fileMatches += [PSCustomObject]@{
                    Line    = $lineNum
                    Pattern = $matchedWord
                    Content = $line.Trim()
                    Context = $context
                }
                $totalMatches++
                break  # Hanya report 1 pattern per baris agar tidak duplikat
            }
        }
    }

    if ($fileMatches.Count -gt 0) {
        $results[$relativePath] = $fileMatches
        $totalFiles++
    }
}

# -------------------------------------------------------------------------
# Tulis laporan ke file Markdown
# -------------------------------------------------------------------------
$reportLines = @()
$reportLines += "# Laporan Teks Bahasa Inggris - Koperasi Majakara"
$reportLines += ""
$reportLines += "> **Tanggal Scan**: $(Get-Date -Format 'dd MMMM yyyy HH:mm')"
$reportLines += "> **Total File Blade**: $($bladeFiles.Count)"
$reportLines += "> **File dengan Teks Inggris**: $totalFiles"
$reportLines += "> **Total Baris Terdeteksi**: $totalMatches"
$reportLines += ""
$reportLines += "---"
$reportLines += ""

foreach ($filePath in ($results.Keys | Sort-Object)) {
    $matches = $results[$filePath]
    $reportLines += "## 📄 ``$filePath``"
    $reportLines += ""
    $reportLines += "| Baris | Kata Terdeteksi | Konteks | Isi Baris |"
    $reportLines += "|-------|-----------------|---------|-----------|"
    foreach ($m in $matches) {
        $safeContent = $m.Content -replace '\|', '\|'
        $reportLines += "| $($m.Line) | ``$($m.Pattern)`` | $($m.Context) | $safeContent |"
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
Write-Host "[SELESAI] Laporan disimpan ke: $outputFile" -ForegroundColor Green
Write-Host ""
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host " Ringkasan Hasil:" -ForegroundColor Cyan
Write-Host "   - Total file blade    : $($bladeFiles.Count)" -ForegroundColor White
Write-Host "   - File berisi Inggris : $totalFiles" -ForegroundColor Yellow
Write-Host "   - Total baris         : $totalMatches" -ForegroundColor Yellow
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""

# Tampilkan preview per folder
Write-Host " Preview per folder:" -ForegroundColor Cyan
foreach ($group in $folderGroups) {
    Write-Host "   [$($group.Name)/]" -ForegroundColor Magenta
    foreach ($f in ($results.Keys | Where-Object { $_ -like "$($group.Name)*" } | Sort-Object)) {
        Write-Host "     - $f ($($results[$f].Count) baris)" -ForegroundColor Gray
    }
}
Write-Host ""
