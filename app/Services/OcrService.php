<?php

namespace App\Services;

use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class OcrService
{
    /**
     * Get Tesseract executable path.
     * 
     * @return string|null
     */
    private function getTesseractPath()
    {
        // Check if Tesseract is in PATH
        $tesseractPath = null;
        
        // Common Windows installation paths
        $possiblePaths = [
            'C:\\Program Files\\Tesseract-OCR\\tesseract.exe',
            'C:\\Program Files (x86)\\Tesseract-OCR\\tesseract.exe',
            env('TESSERACT_PATH', null), // From .env file
        ];
        
        // Check if tesseract is in PATH (Windows)
        $whereCommand = PHP_OS_FAMILY === 'Windows' ? 'where tesseract 2>nul' : 'which tesseract 2>/dev/null';
        $tesseractInPath = shell_exec($whereCommand);
        
        if ($tesseractInPath && trim($tesseractInPath)) {
            return null; // Use default (in PATH)
        }
        
        // Check common paths
        foreach ($possiblePaths as $path) {
            if ($path && file_exists($path)) {
                $tesseractPath = $path;
                break;
            }
        }
        
        return $tesseractPath;
    }

    /**
     * Preprocess KTP image untuk meningkatkan akurasi OCR.
     * WAJIB: Tanpa preprocessing, hasil akan salah.
     *
     * @param string $imagePath Path to original image
     * @return string Path to processed image
     */
    private function preprocessImage($imagePath)
    {
        try {
            // Check if ImageMagick is available
            $magickPath = $this->getImageMagickPath();
            $fullPath = Storage::disk('public')->path($imagePath);
            
            if ($magickPath) {
                // Use ImageMagick (recommended)
                $processedPath = $imagePath . '_processed.png';
                $fullProcessedPath = Storage::disk('public')->path($processedPath);
                
                // ImageMagick command: resize 200%, grayscale, contrast-stretch, threshold
                $command = sprintf(
                    '"%s" "%s" -resize 200%% -colorspace Gray -contrast-stretch 0 -threshold 60%% "%s"',
                    $magickPath,
                    $fullPath,
                    $fullProcessedPath
                );
                
                exec($command, $output, $returnCode);
                
                if ($returnCode === 0 && file_exists($fullProcessedPath)) {
                    return $processedPath;
                }
            }
            
            // Fallback: Return original if ImageMagick not available
            // Note: Preprocessing sangat penting untuk akurasi OCR
            // Install ImageMagick untuk hasil terbaik
            Log::warning('ImageMagick not available. OCR accuracy may be reduced. Install ImageMagick for best results.');
            return $imagePath; // Fallback to original
        } catch (\Exception $e) {
            Log::warning('Image preprocessing error: ' . $e->getMessage());
            return $imagePath; // Fallback to original
        }
    }

    /**
     * Get ImageMagick executable path.
     *
     * @return string|null
     */
    private function getImageMagickPath()
    {
        // Check common ImageMagick paths
        $possiblePaths = [
            'C:\\Program Files\\ImageMagick-7.1.1-Q16-HDRI\\magick.exe',
            'C:\\Program Files\\ImageMagick-7.1.0-Q16-HDRI\\magick.exe',
            'magick', // If in PATH
            env('IMAGEMAGICK_PATH', null),
        ];
        
        foreach ($possiblePaths as $path) {
            if ($path === 'magick') {
                // Check if magick is in PATH
                $result = shell_exec('where magick 2>nul');
                if ($result && trim($result)) {
                    return 'magick';
                }
            } elseif ($path && file_exists($path)) {
                return $path;
            }
        }
        
        return null;
    }

    /**
     * Normalize OCR text (KRITIS untuk akurasi).
     * FIX: JANGAN HAPUS LINE BREAK - ini merusak struktur data.
     * Normalisasi karakter ambigu HANYA pada kandidat numerik.
     *
     * @param string $text Raw OCR text
     * @return string Normalized text
     */
    private function normalizeText($text)
    {
        // Convert to uppercase
        $text = strtoupper($text);

        // NORMALISASI SPASI TANPA MERUSAK BARIS
        $text = preg_replace("/[ \t]+/", " ", $text);

        // NORMALISASI LINE BREAK
        $text = preg_replace("/\r\n|\r/", "\n", $text);

        return trim($text);
    }

    /**
     * Normalize numeric candidates (NIK, tanggal) - handle OCR errors.
     *
     * @param string $text Text containing numeric candidates
     * @return string Normalized text with O/I/L converted to digits
     */
    private function normalizeNumeric($text)
    {
        // Normalisasi karakter ambigu untuk numerik: O→0, I/L→1, |→1
        return str_replace(
            ['O', 'I', 'L', '|'],
            ['0', '1', '1', '1'],
            $text
        );
    }

    /**
     * Extract text from KTP image using Tesseract OCR.
     * Mengikuti baseline stabil yang terbukti bekerja.
     *
     * @param string $imagePath Path to KTP image (storage path)
     * @return array Extracted data from KTP
     */
    public function extractKtpData($imagePath)
    {
        try {
            // Get full path to image
            $fullPath = Storage::disk('public')->path($imagePath);
            
            if (!file_exists($fullPath)) {
                throw new \Exception("Image file not found: {$fullPath}");
            }

            // STEP 1: PREPROCESSING (WAJIB)
            $processedPath = $this->preprocessImage($imagePath);
            $processedFullPath = Storage::disk('public')->path($processedPath);

            // STEP 2: KONFIGURASI TESSERACT (FIX)
            $ocr = new TesseractOCR($processedFullPath);
            
            // Set Tesseract path if not in PATH
            $tesseractPath = $this->getTesseractPath();
            if ($tesseractPath) {
                $ocr->executable($tesseractPath);
            }
            
            // Konfigurasi FIX sesuai baseline
            $ocr->lang('ind'); // Indonesian language
            
            try {
                $ocr->oem(1);     // LSTM engine
            } catch (\Exception $e) {
                Log::warning('OEM mode not supported: ' . $e->getMessage());
            }
            
            try {
                $ocr->psm(6);     // Uniform block (JANGAN pakai PSM 11)
            } catch (\Exception $e) {
                Log::warning('PSM mode not supported: ' . $e->getMessage());
            }
            
            try {
                $ocr->dpi(300);
            } catch (\Exception $e) {
                Log::warning('DPI setting not supported: ' . $e->getMessage());
            }
            
            // Run OCR
            $rawText = $ocr->run();

            // STEP 3: NORMALISASI TEKS (KRITIS)
            $normalizedText = $this->normalizeText($rawText);

            // VALIDASI STRUKTUR SETELAH NORMALISASI
            $lines = explode("\n", $normalizedText);
            Log::info('OCR Raw Text:', ['text' => $rawText]);
            Log::info('OCR Normalized Text:', ['text' => $normalizedText]);
            Log::info('OCR LINES COUNT:', ['count' => count($lines), 'lines' => $lines]);

            // STEP 4: EKSTRAKSI DATA (FIX)
            $ktpData = $this->parseKtpText($normalizedText);

            // Log parsed data for debugging
            Log::info('OCR Parsed Data:', $ktpData);

            // Cleanup processed image
            if ($processedPath !== $imagePath && file_exists($processedFullPath)) {
                @unlink($processedFullPath);
            }

            return [
                'success' => true,
                'raw_text' => $rawText,
                'data' => $ktpData
            ];
        } catch (\Exception $e) {
            Log::error('OCR Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Extract date from NIK (digits 7-12).
     * Format: YYMMDD
     *
     * @param string $nik 16-digit NIK
     * @return array|null ['year', 'month', 'day'] or null
     */
    private function extractDateFromNik($nik)
    {
        if (strlen($nik) < 12) {
            return null;
        }

        $yy = substr($nik, 6, 2);
        $mm = substr($nik, 8, 2);
        $dd = substr($nik, 10, 2);

        // Convert 2-digit year to 4-digit (assume 1900-2099)
        $year = (int)$yy;
        if ($year < 50) {
            $year += 2000;
        } else {
            $year += 1900;
        }

        // Check if day >= 40 (indicates female)
        $day = (int)$dd;
        if ($day >= 40) {
            $day -= 40; // Female adjustment
        }

        // Validate date
        if ($mm >= 1 && $mm <= 12 && $day >= 1 && $day <= 31) {
            return [
                'year' => $year,
                'month' => (int)$mm,
                'day' => $day
            ];
        }

        return null;
    }

    /**
     * Validate NIK candidate.
     *
     * @param string $nik 16-digit NIK candidate
     * @return bool
     */
    private function validateNik($nik)
    {
        if (strlen($nik) !== 16 || !preg_match('/^\d{16}$/', $nik)) {
            return false;
        }

        // Validate date from NIK
        $date = $this->extractDateFromNik($nik);
        return $date !== null;
    }

    /**
     * Parse OCR text to extract KTP fields.
     * PROMPT FINAL: Handle blur/low light OCR dengan logika struktur KTP Indonesia.
     *
     * @param string $text Normalized OCR text (sudah di-normalize)
     * @return array Parsed KTP data
     */
    private function parseKtpText($text)
    {
        $data = [
            'nik' => null,
            'nama_lengkap' => null,
            'tempat_lahir' => null,
            'tanggal_lahir' => null,
            'jenis_kelamin' => null,
            'alamat' => null,
        ];

        // Normalize line breaks
        $text = preg_replace('/\r\n|\r/', "\n", $text);
        $lines = explode("\n", $text);

        // STEP 1: EKSTRAKSI NIK (dengan validasi internal)
        // Cari semua kandidat 15-17 digit (termasuk O/I/L)
        $nikCandidates = [];
        
        // Normalisasi numerik untuk pencarian NIK
        $numericText = $this->normalizeNumeric($text);
        
        // Cari pattern 15-17 digit
        if (preg_match_all('/\b[\dOIL|]{15,17}\b/', $numericText, $matches)) {
            foreach ($matches[0] as $candidate) {
                $normalized = $this->normalizeNumeric($candidate);
                if (strlen($normalized) >= 15 && strlen($normalized) <= 17) {
                    // Pad atau trim to 16 digits
                    if (strlen($normalized) < 16) {
                        $normalized = str_pad($normalized, 16, '0', STR_PAD_RIGHT);
                    } elseif (strlen($normalized) > 16) {
                        $normalized = substr($normalized, 0, 16);
                    }
                    
                    if ($this->validateNik($normalized)) {
                        $nikCandidates[] = $normalized;
                    }
                }
            }
        }
        
        // Pilih NIK yang valid (prioritas: yang pertama ditemukan dan valid)
        if (!empty($nikCandidates)) {
            $data['nik'] = $nikCandidates[0];
        }

        // STEP 2: EKSTRAKSI NAMA LENGKAP
        // Prioritas: 1) Baris tepat setelah NIK, 2) Baris huruf terpanjang yang lolos filter
        $namaCandidates = [];
        
        foreach ($lines as $i => $line) {
            $line = trim($line);
            
            // Skip baris yang jelas bukan nama
            if (preg_match('/^[0-9\-\s:]+$/', $line) || // Hanya angka/dash
                preg_match('/^[^A-Z]*$/', $line) || // Tidak ada huruf besar
                preg_match('/PROVINSI|KABUPATEN|KOTA|KECAMATAN|NIK|TEMPAT|LAHIR|ALAMAT|AGAMA/', $line)) {
                continue;
            }

            // Bersihkan karakter khusus dan angka
            $cleanedLine = preg_replace('/^[-:\s]+/', '', $line);
            $cleanedLine = preg_replace('/[-:\s]+$/', '', $cleanedLine);
            $cleanedLine = preg_replace('/[0-9\-—–]+/', ' ', $cleanedLine);
            $cleanedLine = preg_replace('/\s+/', ' ', $cleanedLine);
            $cleanedLine = trim($cleanedLine);
            
            // Validasi: 5-40 karakter, hanya huruf dan spasi, bukan kata administratif
            if (
                strlen($cleanedLine) >= 5 &&
                strlen($cleanedLine) <= 40 &&
                preg_match('/^[A-Z][A-Z\s]+$/', $cleanedLine) &&
                !preg_match('/PROVINSI|KABUPATEN|KOTA|KECAMATAN|ALAMAT|TEMPAT|LAHIR|NIK|AGAMA/', $cleanedLine)
            ) {
                $priority = 0;
                
                // Prioritas 1: Baris tepat setelah NIK
                if ($data['nik']) {
                    if (isset($lines[$i - 1]) && str_contains($lines[$i - 1], $data['nik'])) {
                        $priority = 100; // Highest priority
                    } elseif (isset($lines[$i - 2]) && str_contains($lines[$i - 2], $data['nik'])) {
                        $priority = 50; // Second priority
                    }
                }
                
                // Prioritas 2: Panjang (huruf terpanjang)
                $priority += strlen($cleanedLine);
                
                $namaCandidates[] = [
                    'nama' => $cleanedLine,
                    'priority' => $priority,
                    'index' => $i
                ];
            }
        }
        
        // Pilih nama dengan priority tertinggi
        if (!empty($namaCandidates)) {
            usort($namaCandidates, function($a, $b) {
                return $b['priority'] - $a['priority'];
            });
            $data['nama_lengkap'] = $namaCandidates[0]['nama'];
        }

        // STEP 3: EKSTRAKSI TEMPAT & TANGGAL LAHIR
        // Cari pola: [KOTA/KABUPATEN] DD-MM-YYYY atau terpisah baris
        $foundTempatTanggal = false;
        
        // Gabungkan baris untuk pencarian (maksimal 3 baris)
        for ($i = 0; $i < count($lines) - 2; $i++) {
            $combined = trim($lines[$i] . ' ' . $lines[$i + 1] . ' ' . $lines[$i + 2]);
            $numericCombined = $this->normalizeNumeric($combined);
            
            // Pattern 1: Format standar "BEKASI, 15-12-2007" atau "BEKASI 15/12/2007"
            if (preg_match(
                '/([A-Z][A-Z\s]{2,25})[, ]+(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})/',
                $combined,
                $matches
            )) {
                $tempat = trim($matches[1]);
                if (!preg_match('/PROVINSI|KABUPATEN|KOTA|KECAMATAN|NIK|TEMPAT|LAHIR|ALAMAT/', $tempat)) {
                    $data['tempat_lahir'] = $tempat;
                    $day = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                    $month = str_pad($matches[3], 2, '0', STR_PAD_LEFT);
                    $year = $matches[4];
                    $data['tanggal_lahir'] = "{$year}-{$month}-{$day}";
                    $foundTempatTanggal = true;
                    break;
                }
            }
            
            // Pattern 2: Format OCR error "SBEKAS11512-2007" (tanpa spasi)
            if (preg_match(
                '/([A-Z][A-Z]{3,20})(\d{1,2})(\d{2})[-\/](\d{4})/',
                $numericCombined,
                $matches
            )) {
                $tempat = trim($matches[1]);
                if (!preg_match('/PROVINSI|KABUPATEN|KOTA|KECAMATAN|NIK|TEMPAT|LAHIR|ALAMAT/', $tempat) &&
                    strlen($tempat) >= 3) {
                    $data['tempat_lahir'] = $tempat;
                    $day = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                    $month = str_pad($matches[3], 2, '0', STR_PAD_LEFT);
                    $year = $matches[4];
                    $data['tanggal_lahir'] = "{$year}-{$month}-{$day}";
                    $foundTempatTanggal = true;
                    break;
                }
            }
            
            // Pattern 3: Format dengan label "TEMPAT" atau "TEMPAGI" (OCR error)
            if (preg_match(
                '/TEMPAT[AGI0-9]*[:\s]+[0-9]*\s+([A-Z][A-Z]{3,20})(\d{1,2})(\d{2})[-\/](\d{4})/i',
                $numericCombined,
                $matches
            )) {
                $tempat = trim($matches[1]);
                if (!preg_match('/PROVINSI|KABUPATEN|KOTA|KECAMATAN|NIK|TEMPAT|LAHIR|ALAMAT/', $tempat) &&
                    strlen($tempat) >= 3) {
                    $data['tempat_lahir'] = $tempat;
                    $day = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                    $month = str_pad($matches[3], 2, '0', STR_PAD_LEFT);
                    $year = $matches[4];
                    $data['tanggal_lahir'] = "{$year}-{$month}-{$day}";
                    $foundTempatTanggal = true;
                    break;
                }
            }
        }
        
        // Jika gagal, ambil dari NIK dan cari tempat lahir terdekat
        if (!$foundTempatTanggal && $data['nik']) {
            $dateFromNik = $this->extractDateFromNik($data['nik']);
            if ($dateFromNik) {
                $data['tanggal_lahir'] = sprintf(
                    "%04d-%02d-%02d",
                    $dateFromNik['year'],
                    $dateFromNik['month'],
                    $dateFromNik['day']
                );
                
                // Cari tempat lahir dari kata huruf besar terdekat sebelum tanggal
                foreach ($lines as $line) {
                    if (preg_match('/([A-Z][A-Z\s]{3,25})/', $line, $matches)) {
                        $tempat = trim($matches[1]);
                        if (!preg_match('/PROVINSI|KABUPATEN|KOTA|KECAMATAN|NIK|TEMPAT|LAHIR|ALAMAT|AGAMA/', $tempat) &&
                            strlen($tempat) >= 3) {
                            $data['tempat_lahir'] = $tempat;
                            break;
                        }
                    }
                }
            }
        }

        // STEP 4: EKSTRAKSI JENIS KELAMIN
        // Prioritas 1: Teks "LAKI-LAKI" atau "PEREMPUAN" (toleran typo)
        $foundJenisKelamin = false;
        
        if (preg_match('/LAKI[-\s]?LAKI|LAKI/i', $text)) {
            $data['jenis_kelamin'] = 'Laki-laki';
            $foundJenisKelamin = true;
        } elseif (preg_match('/PEREMPUAN|PEREMPUAN/i', $text)) {
            $data['jenis_kelamin'] = 'Perempuan';
            $foundJenisKelamin = true;
        }
        
        // Prioritas 2: Inferensi dari NIK (hari lahir +40 = perempuan)
        if (!$foundJenisKelamin && $data['nik']) {
            $dateFromNik = $this->extractDateFromNik($data['nik']);
            if ($dateFromNik) {
                // Check original day from NIK (before adjustment)
                $originalDay = (int)substr($data['nik'], 10, 2);
                if ($originalDay >= 40) {
                    $data['jenis_kelamin'] = 'Perempuan';
                } else {
                    $data['jenis_kelamin'] = 'Laki-laki';
                }
            }
        }

        // STEP 5: EKSTRAKSI ALAMAT (MULTILINE)
        $alamat = '';
        $collect = false;
        $alamatStartIndex = null;

        // Cari baris yang berisi "ALAMAT"
        foreach ($lines as $i => $line) {
            $line = trim($line);
            
            // Deteksi awal alamat dengan label
            if (preg_match('/^ALAMAT/i', $line)) {
                $collect = true;
                $alamatStartIndex = $i;
                // Ambil teks setelah "ALAMAT"
                if (preg_match('/ALAMAT[:\s]+(.+)/i', $line, $matches)) {
                    $alamat .= ' ' . trim($matches[1]);
                }
                continue;
            }

            if ($collect) {
                // Stop jika menemukan field berikutnya
                if (preg_match('/AGAMA|PEKERJAAN|KEWARGANEGARAAN|BERLAKU|STATUS|GOLONGAN/i', $line)) {
                    break;
                }
                
                // Skip baris yang jelas bukan alamat
                if (preg_match('/^[0-9\-\s:]+$/', $line) || // Hanya angka
                    preg_match('/PROVINSI|KABUPATEN|KOTA|KECAMATAN|NIK|TEMPAT|LAHIR/', $line)) {
                    continue;
                }
                
                // Bersihkan karakter khusus
                $cleanedLine = preg_replace('/^[-:\s]+/', '', $line);
                $cleanedLine = preg_replace('/[-:\s]+$/', '', $cleanedLine);
                
                if (strlen($cleanedLine) > 3) {
                    $alamat .= ' ' . $cleanedLine;
                }
            }
        }

        // Jika label "ALAMAT" hilang, ambil blok teks tengah
        if (empty(trim($alamat)) && count($lines) > 4) {
            $middleStart = (int)(count($lines) * 0.3); // 30% dari atas
            $middleEnd = (int)(count($lines) * 0.8); // 80% dari atas
            
            for ($i = $middleStart; $i < $middleEnd; $i++) {
                $line = trim($lines[$i]);
                
                // Skip baris yang jelas bukan alamat
                if (preg_match('/^[0-9\-\s:]+$/', $line) ||
                    preg_match('/PROVINSI|KABUPATEN|KOTA|KECAMATAN|NIK|TEMPAT|LAHIR|ALAMAT|AGAMA/', $line) ||
                    strlen($line) < 5) {
                    continue;
                }
                
                // Cek apakah mengandung pola RT/RW atau panjang > 20
                if (strlen($line) > 20 || preg_match('/RT|RW|JALAN|JL|GANG|GG|NO|BLOK/i', $line)) {
                    $cleanedLine = preg_replace('/^[-:\s]+/', '', $line);
                    $cleanedLine = preg_replace('/[-:\s]+$/', '', $cleanedLine);
                    
                    if (strlen($cleanedLine) > 3) {
                        $alamat .= ' ' . $cleanedLine;
                    }
                }
            }
        }

        $data['alamat'] = trim($alamat);

        return $data;
    }

    /**
     * Extract text from image (generic).
     *
     * @param string $imagePath
     * @return string Extracted text
     */
    public function extractText($imagePath)
    {
        try {
            $fullPath = Storage::disk('public')->path($imagePath);
            
            if (!file_exists($fullPath)) {
                throw new \Exception("Image file not found: {$fullPath}");
            }

            $ocr = new TesseractOCR($fullPath);
            
            // Set Tesseract path if not in PATH
            $tesseractPath = $this->getTesseractPath();
            if ($tesseractPath) {
                $ocr->executable($tesseractPath);
            }
            
            $ocr->lang('ind');
            return $ocr->run();
        } catch (\Exception $e) {
            Log::error('OCR Error: ' . $e->getMessage());
            return '';
        }
    }
}