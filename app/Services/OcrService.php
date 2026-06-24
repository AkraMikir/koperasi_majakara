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
        $tesseractPath = null;
        
        $possiblePaths = [
            'C:\\Program Files\\Tesseract-OCR\\tesseract.exe',
            'C:\\Program Files (x86)\\Tesseract-OCR\\tesseract.exe',
            env('TESSERACT_PATH', null),
        ];
        
        $whereCommand = PHP_OS_FAMILY === 'Windows' ? 'where tesseract 2>nul' : 'which tesseract 2>/dev/null';
        $tesseractInPath = shell_exec($whereCommand);
        
        if ($tesseractInPath && trim($tesseractInPath)) {
            return null;
        }
        
        foreach ($possiblePaths as $path) {
            if ($path && file_exists($path)) {
                $tesseractPath = $path;
                break;
            }
        }
        
        return $tesseractPath;
    }

    /**
     * Extract text from KTP image using Tesseract OCR.
     *
     * @param string $imagePath Path to KTP image (storage path)
     * @return array Extracted data from KTP
     */
    public function extractKtpData($imagePath)
    {
        $ocrInputPath = null;
        $cleanFileCreated = false;
        
        try {
            $fullPath = Storage::disk('public')->path($imagePath);
            
            if (!file_exists($fullPath)) {
                throw new \Exception("Image file not found: {$fullPath}");
            }

            $ocrInputPath = $fullPath;

            // 1. Prapemrosesan Gambar dengan Imagick
            if (class_exists('Imagick')) {
                try {
                    $imagick = new \Imagick($fullPath);
                    
                    // Grayscale
                    $imagick->modulateImage(100, 0, 100);
                    
                    // Tambah Kontras
                    $imagick->contrastImage(true);
                    
                    // Binarization/Thresholding
                    $imagick->thresholdImage(0.6 * \Imagick::getQuantum());
                    
                    // Simpan gambar bersih ke temporary file baru
                    $tempCleanName = 'clean_ktp_' . time() . '_' . basename($imagePath);
                    $tempCleanPath = dirname($fullPath) . DIRECTORY_SEPARATOR . $tempCleanName;
                    
                    $imagick->writeImage($tempCleanPath);
                    $imagick->clear();
                    $imagick->destroy();
                    
                    $ocrInputPath = $tempCleanPath;
                    $cleanFileCreated = true;
                    Log::info("OCR: Imagick preprocessing success. Clean image saved to: {$ocrInputPath}");
                } catch (\Exception $e) {
                    Log::warning("OCR: Imagick preprocessing failed: " . $e->getMessage() . ". Proceeding with raw image.");
                }
            } else {
                Log::warning("OCR: Imagick extension not loaded. Proceeding with raw image.");
            }

            // Run OCR
            $ocr = new TesseractOCR($ocrInputPath);
            
            $tesseractPath = $this->getTesseractPath();
            if ($tesseractPath) {
                $ocr->executable($tesseractPath);
            }
            
            $tessdataDir = null;
            $tessdataEnv = env('TESSDATA_DIR') ?: env('TESSDATA_PREFIX');
            if ($tessdataEnv) {
                $tessdataDir = rtrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $tessdataEnv), DIRECTORY_SEPARATOR);
                if (!str_ends_with(strtolower($tessdataDir), 'tessdata')) {
                    $tessdataDir .= DIRECTORY_SEPARATOR . 'tessdata';
                }
                if (is_dir($tessdataDir)) {
                    $ocr->tessdataDir($tessdataDir);
                }
            }
            
            // Tesseract PSM 4
            $ocr->psm(4);
            
            try {
                $ocr->dpi(300);
            } catch (\Exception $e) {
                Log::warning('DPI setting not supported: ' . $e->getMessage());
            }
            
            $text = null;
            $usedLang = 'ind';
            $lastException = null;
            
            foreach (['ind', 'eng'] as $lang) {
                $ocrTry = new TesseractOCR($ocrInputPath);
                if ($tesseractPath) {
                    $ocrTry->executable($tesseractPath);
                }
                if ($tessdataDir && is_dir($tessdataDir)) {
                    $ocrTry->tessdataDir($tessdataDir);
                }
                
                $ocrTry->psm(4);
                
                try {
                    $ocrTry->dpi(300);
                } catch (\Exception $e) { /* ignore */ }
                
                $ocrTry->lang($lang);
                try {
                    $text = $ocrTry->run();
                    $usedLang = $lang;
                    break;
                } catch (\Exception $e) {
                    $lastException = $e;
                    $msg = $e->getMessage();
                    if (($lang === 'ind') && (str_contains($msg, 'Failed loading language') || str_contains($msg, 'ind.traineddata') || str_contains($msg, 'tessdata') || str_contains($msg, "Could not load"))) {
                        Log::warning('OCR: Indonesian (ind) not available, trying English. ' . $msg);
                        continue;
                    }
                    throw $e;
                }
            }
            
            if ($text === null) {
                $hint = $lastException ? $lastException->getMessage() : '';
                throw new \Exception('Tesseract tidak dapat memuat bahasa. ' . $hint);
            }

            Log::info('OCR Raw Text:', ['text' => $text]);

            // Ekstraksi dan Pembersihan (Post-processing)
            $ktpData = $this->parseKtpText($text);

            Log::info('OCR Parsed Data:', $ktpData);

            return [
                'success' => true,
                'raw_text' => $text,
                'data' => $ktpData
            ];
        } catch (\Exception $e) {
            Log::error('OCR Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'data' => []
            ];
        } finally {
            // Bersihkan file temporary hasil preprocessing Imagick
            if ($cleanFileCreated && $ocrInputPath && file_exists($ocrInputPath)) {
                @unlink($ocrInputPath);
                Log::info("OCR: Cleaned temporary preprocessed image: {$ocrInputPath}");
            }
        }
    }

    /**
     * Parse OCR text to extract KTP fields with fault-tolerant fuzzy matches.
     *
     * @param string $text Raw OCR text
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
            'alamat_lengkap' => null,
            'rt_rw' => null,
            'kelurahan_desa' => null,
            'kecamatan' => null,
        ];

        // Normalisasi whitespace dan line break
        $text = preg_replace('/[ \t]+/', ' ', $text);
        $text = preg_replace('/\r\n|\r/', "\n", $text);
        
        // Buang watermark/provinsi/republik
        $text = preg_replace('#(DUK\s*KEPENDUDUK|INDONESIA|ORTU|STU\s*T|PROVINSI|KABUPATEN|KOTA|REPUBLIK)#i', '', $text);
        
        $lines = explode("\n", $text);
        
        $cleanedLines = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || preg_match('#^[-:\s]+$#', $line)) {
                continue;
            }
            $cleanedLines[] = $line;
        }
        $lines = $cleanedLines;

        // Translation table untuk membersihkan kesalahan baca karakter angka
        $numTranslation = [
            'O' => '0', 'o' => '0', 'D' => '0',
            'I' => '1', 'l' => '1', '|' => '1',
            'S' => '5', 's' => '5',
            'B' => '8', 'b' => '8',
            '?' => '7',
            'Z' => '2'
        ];

        // 1. NIK (Fuzzy & aman dari auto-translate label NIK)
        foreach ($lines as $line) {
            if (stripos($line, 'NIK') !== false) {
                $parts = explode(':', $line, 2);
                $valPart = trim(end($parts));
                $cleanedVal = strtr($valPart, $numTranslation);
                $digitsOnly = preg_replace('/\D/', '', $cleanedVal);
                if (strlen($digitsOnly) >= 16) {
                    $data['nik'] = substr($digitsOnly, 0, 16);
                    break;
                }
            }
        }
        if (empty($data['nik'])) {
            foreach ($lines as $line) {
                $parts = explode(':', $line, 2);
                $valPart = trim(end($parts));
                $cleanedVal = strtr($valPart, $numTranslation);
                $digitsOnly = preg_replace('/\D/', '', $cleanedVal);
                if (strlen($digitsOnly) >= 16) {
                    $data['nik'] = substr($digitsOnly, 0, 16);
                    break;
                }
            }
        }

        // 2. Nama Lengkap (Fuzzy label + fallback line di bawah NIK jika label rusak total)
        foreach ($lines as $index => $line) {
            if (preg_match('/(?:Nama|Narn|Nema|Mama|Nara|Narna|Name|Nema)\s*[-:=.|!]*\s*(.*)/i', $line, $matches)) {
                $candidate = trim($matches[1]);
                if (strlen($candidate) > 2) {
                    $data['nama_lengkap'] = $candidate;
                    break;
                }
            }
        }
        if (empty($data['nama_lengkap']) && !empty($data['nik'])) {
            foreach ($lines as $index => $line) {
                if (str_contains($line, $data['nik'])) {
                    if (isset($lines[$index + 1])) {
                        $candidate = trim($lines[$index + 1]);
                        $parts = explode(':', $candidate);
                        $candidateValue = trim(end($parts));
                        if (strlen($candidateValue) > 3 && !preg_match('/(tempat|tgl|lahir|alamat|kelamin|nik)/i', $candidateValue)) {
                            $data['nama_lengkap'] = $candidateValue;
                            break;
                        }
                    }
                }
            }
        }
        if ($data['nama_lengkap']) {
            $data['nama_lengkap'] = trim(preg_replace('/^[-:=.|!\s]+|[-:=.|!\s]+$/', '', $data['nama_lengkap']));
        }

        // 3 & 4. Tempat & Tanggal Lahir (Fuzzy label)
        foreach ($lines as $line) {
            if (preg_match('/(?:Tempat|Tempet|Tgl|Tanggal|Lahir|Lhr|Tgl\s*Lahir)[^:]*[-:=.|!]+\s*(.*)/i', $line, $matches)) {
                $content = trim($matches[1]);
                $parts = explode(',', $content);
                if (count($parts) >= 2) {
                    $data['tempat_lahir'] = trim(preg_replace('/^[-:=.|!\s]+/', '', $parts[0]));
                    
                    $datePartRaw = trim($parts[1]);
                    $datePartClean = strtr($datePartRaw, $numTranslation);
                    
                    if (preg_match('/(\d{2})[-\/](\d{2})[-\/](\d{4})/', $datePartClean, $dateMatches)) {
                        $day = $dateMatches[1];
                        $month = $dateMatches[2];
                        $year = $dateMatches[3];
                        $data['tanggal_lahir'] = "{$year}-{$month}-{$day}";
                    }
                }
                break;
            }
        }

        // 5. Jenis Kelamin (Direct value scanning - lebih aman daripada regex label)
        foreach ($lines as $line) {
            $upperLine = strtoupper($line);
            if (strpos($upperLine, 'LAKI') !== false || strpos($upperLine, 'LAKl') !== false) {
                $data['jenis_kelamin'] = 'LAKI-LAKI';
                break;
            } elseif (strpos($upperLine, 'PEREMPUAN') !== false || strpos($upperLine, 'PEREMPUN') !== false || strpos($upperLine, 'PEREM') !== false) {
                $data['jenis_kelamin'] = 'PEREMPUAN';
                break;
            }
        }

        // 6. Alamat Lengkap (Fuzzy label)
        foreach ($lines as $index => $line) {
            if (preg_match('/(?:Alamat|Alamet|Almat)\s*[-:=.|!]+\s*(.*)/i', $line, $matches)) {
                $candidate = trim($matches[1]);
                if (strlen($candidate) > 2) {
                    $data['alamat_lengkap'] = $candidate;
                    break;
                }
            }
        }
        if ($data['alamat_lengkap']) {
            $data['alamat_lengkap'] = trim(preg_replace('/^[-:=.|!\s]+|[-:=.|!\s]+$/', '', $data['alamat_lengkap']));
        }

        // 7. RT/RW (Aman dari bentrokan kata RT dalam "JAKARTA")
        foreach ($lines as $line) {
            $parts = explode(':', $line, 2);
            $label = trim($parts[0]);
            $val = isset($parts[1]) ? trim($parts[1]) : '';
            
            if (empty($val)) {
                $val = $label;
            }
            
            if (preg_match('/\b(?:RT|RW)\b/i', $label) || preg_match('/RT\s*[\/.\-_]\s*RW/i', $label) || (empty($parts[1]) && preg_match('/RT\s*[\/.\-_]\s*RW/i', $line))) {
                $cleanedVal = strtr($val, $numTranslation);
                if (preg_match('/(\d{3})\s*[\/-]\s*(\d{3})/', $cleanedVal, $rtrwMatches)) {
                    $data['rt_rw'] = "{$rtrwMatches[1]}/{$rtrwMatches[2]}";
                    break;
                } elseif (preg_match('/(\d+)\s*[\/-]\s*(\d+)/', $cleanedVal, $rtrwMatches)) {
                    $rt = str_pad($rtrwMatches[1], 3, '0', STR_PAD_LEFT);
                    $rw = str_pad($rtrwMatches[2], 3, '0', STR_PAD_LEFT);
                    $data['rt_rw'] = "{$rt}/{$rw}";
                    break;
                }
            }
        }

        // 8. Kelurahan/Desa (Fuzzy label + exclude "Kelamin" & "Jenis")
        foreach ($lines as $line) {
            if (stripos($line, 'Kelamin') !== false || stripos($line, 'Jenis') !== false) {
                continue;
            }
            $parts = explode(':', $line, 2);
            $label = trim($parts[0]);
            $val = isset($parts[1]) ? trim($parts[1]) : '';
            
            if (preg_match('/(?:Kel\/Desa|Kel\/Dasa|KeliDesa|Kel\/Oesa|Kel\s*[\/.]?\s*Desa|Kelurahan|Desa)\b/i', $label)) {
                $data['kelurahan_desa'] = trim(preg_replace('/^[-:=.|!\s]+|[-:=.|!\s]+$/', '', $val));
                break;
            }
        }

        // 9. Kecamatan (Fuzzy label dengan strict word boundaries)
        foreach ($lines as $line) {
            $parts = explode(':', $line, 2);
            $label = trim($parts[0]);
            $val = isset($parts[1]) ? trim($parts[1]) : '';
            
            if (preg_match('/\b(?:Kecamatan|Kecamaten|Kecamalan|Kec)\b/i', $label)) {
                $data['kecamatan'] = trim(preg_replace('/^[-:=.|!\s]+|[-:=.|!\s]+$/', '', $val));
                break;
            }
        }

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
            
            $tesseractPath = $this->getTesseractPath();
            if ($tesseractPath) {
                $ocr->executable($tesseractPath);
            }
            
            $ocr->lang('ind');
            $ocr->psm(4);
            return $ocr->run();
        } catch (\Exception $e) {
            Log::error('OCR Error: ' . $e->getMessage());
            return '';
        }
    }
}