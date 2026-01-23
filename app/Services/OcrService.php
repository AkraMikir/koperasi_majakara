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
     * Extract text from KTP image using Tesseract OCR.
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

            // Run OCR with Indonesian language
            $ocr = new TesseractOCR($fullPath);
            
            // Set Tesseract path if not in PATH
            $tesseractPath = $this->getTesseractPath();
            if ($tesseractPath) {
                $ocr->executable($tesseractPath);
            }
            
            // Configure OCR for better accuracy
            $ocr->lang('ind'); // Indonesian language
            
            // Page Segmentation Mode: 6 = Assume uniform block of text
            // This works well for KTP which has structured layout
            try {
                $ocr->psm(6);
            } catch (\Exception $e) {
                // PSM might not be supported in older Tesseract versions, continue without it
                Log::warning('PSM mode not supported: ' . $e->getMessage());
            }
            
            // Set DPI (dots per inch) - higher DPI = better quality
            // KTP images are usually scanned at 300 DPI
            try {
                $ocr->dpi(300);
            } catch (\Exception $e) {
                // DPI might not be supported, continue without it
                Log::warning('DPI setting not supported: ' . $e->getMessage());
            }
            
            // Run OCR
            $text = $ocr->run();

            // Log raw OCR text for debugging
            Log::info('OCR Raw Text:', ['text' => $text]);

            // Parse extracted text to get KTP data
            $ktpData = $this->parseKtpText($text);

            // Log parsed data for debugging
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
        }
    }

    /**
     * Parse OCR text to extract KTP fields.
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
            'alamat' => null,
        ];

        // Preprocess text: normalize whitespace but keep line breaks
        $text = preg_replace('/[ \t]+/', ' ', $text); // Normalize spaces/tabs
        $text = preg_replace('/\r\n|\r/', "\n", $text); // Normalize line breaks
        
        // Remove common OCR noise/watermarks
        $text = preg_replace('#(DUK\s*KEPENDUDUK|INDONESIA|ORTU|STU\s*T|PROVINSI|KABUPATEN|KOTA|REPUBLIK)#i', '', $text);
        
        $lines = explode("\n", $text);
        
        // Clean each line - remove excessive dashes and special characters at start/end
        $cleanedLines = [];
        foreach ($lines as $line) {
            $line = trim($line);
            // Remove lines that are just dashes or special characters
            if (preg_match('#^[-:\s]+$#', $line)) {
                continue;
            }
            // Remove excessive leading/trailing dashes and colons
            $line = preg_replace('#^[-:\s]+#', '', $line);
            $line = preg_replace('#[-:\s]+$#', '', $line);
            if (!empty($line) && strlen($line) > 1) {
                $cleanedLines[] = $line;
            }
        }
        $lines = $cleanedLines;

        // Step 1: Extract NIK (try multiple patterns)
        foreach ($lines as $line) {
            // Pattern 1: NIK: 3275021403080006
            if (preg_match('#NIK[:\s]*(\d{16})#i', $line, $matches)) {
                $data['nik'] = $matches[1];
                break;
            }
            // Pattern 2: Just 16 digits at start of line
            if (preg_match('#^(\d{16})#', $line, $matches)) {
                $data['nik'] = $matches[1];
                break;
            }
            // Pattern 3: 16 digits with possible spaces (OCR sometimes adds spaces)
            if (preg_match('#(\d(?:\s?\d){15})#', $line, $matches)) {
                $nik = preg_replace('/\s+/', '', $matches[1]);
                if (strlen($nik) == 16) {
                    $data['nik'] = $nik;
                    break;
                }
            }
        }

        // Fallback: Extract NIK from entire text (remove all non-digits first)
        if (empty($data['nik'])) {
            $digitsOnly = preg_replace('/\D/', '', $text);
            // Find first 16 consecutive digits
            if (preg_match('#(\d{16})#', $digitsOnly, $matches)) {
                $data['nik'] = $matches[1];
            }
        }

        // Step 2: Extract Nama Lengkap (multiple strategies with better cleaning)
        foreach ($lines as $index => $line) {
            // Strategy 1: After "Nama" label
            if (preg_match('#Nama(?:\s+Lengkap)?[:\s]*([A-Z][A-Z\s]{3,50}?)(?:\s+(?:Tempat|Tanggal|Lahir|Jenis|Kelamin|Agama|Pekerjaan)|$)#i', $line, $matches)) {
                $nama = trim($matches[1]);
                // Clean up: remove common prefixes and suffixes
                $nama = preg_replace('#^(Nama|Nama\s+Lengkap|[-:\s]+)#i', '', $nama);
                $nama = preg_replace('#([-:\s,]+)$#', '', $nama); // Remove trailing dashes, colons, commas
                // Remove any remaining special characters at start/end
                $nama = preg_replace('#^[-:\s,]+#', '', $nama);
                $nama = preg_replace('#[-:\s,]+$#', '', $nama);
                if (strlen($nama) > 3 && !preg_match('#^\d#', $nama) && !preg_match('#(Tempat|Tanggal|Lahir|Jenis|Kelamin|Agama|Pekerjaan)#i', $nama)) {
                    $data['nama_lengkap'] = $nama;
                    break;
                }
            }
            
            // Strategy 2: Line after NIK line (usually nama) - but check if it's actually a name
            if (!empty($data['nik']) && $index > 0) {
                $prevLine = isset($lines[$index - 1]) ? $lines[$index - 1] : '';
                // Check if previous line contains NIK
                if (preg_match('#\b' . preg_quote($data['nik'], '#') . '\b#', $prevLine) || 
                    preg_match('#NIK#i', $prevLine)) {
                    // This line might be nama if it's all caps, reasonable length, and doesn't contain KTP labels
                    $cleanedLine = preg_replace('#^[-:\s]+#', '', $line);
                    $cleanedLine = preg_replace('#[-:\s,]+$#', '', $cleanedLine);
                    if (preg_match('#^[A-Z][A-Z\s]{4,50}$#', $cleanedLine) && 
                        !preg_match('#\d#', $cleanedLine) &&
                        !preg_match('#(NIK|KTP|PROVINSI|KABUPATEN|KOTA|KECAMATAN|KELURAHAN|REPUBLIK|INDONESIA|TEMPAT|TANGGAL|LAHIR|JENIS|KELAMIN|AGAMA|PEKERJAAN|KEWARGANEGARAAN|BERLAKU|RT|RW|Alamat)#i', $cleanedLine)) {
                        $data['nama_lengkap'] = $cleanedLine;
                        break;
                    }
                }
            }
        }

        // Fallback: Find lines that look like names (all caps, 5-60 chars, no numbers, no KTP labels)
        if (empty($data['nama_lengkap'])) {
            foreach ($lines as $line) {
                $cleanedLine = preg_replace('#^[-:\s,]+#', '', $line);
                $cleanedLine = preg_replace('#[-:\s,]+$#', '', $cleanedLine);
                // All uppercase, 5-60 characters, no numbers, no KTP field labels
                if (preg_match('#^[A-Z][A-Z\s]{4,59}$#', $cleanedLine) && 
                    !preg_match('#\d#', $cleanedLine) &&
                    !preg_match('#(NIK|KTP|PROVINSI|KABUPATEN|KOTA|KECAMATAN|KELURAHAN|REPUBLIK|INDONESIA|TEMPAT|TANGGAL|LAHIR|JENIS|KELAMIN|AGAMA|PEKERJAAN|KEWARGANEGARAAN|BERLAKU|RT|RW|Alamat|Nama)#i', $cleanedLine)) {
                    $data['nama_lengkap'] = $cleanedLine;
                    break;
                }
            }
        }
        
        // Final cleanup for nama: remove any remaining unwanted characters
        if (!empty($data['nama_lengkap'])) {
            $data['nama_lengkap'] = preg_replace('#^[-:\s,]+#', '', $data['nama_lengkap']);
            $data['nama_lengkap'] = preg_replace('#[-:\s,]+$#', '', $data['nama_lengkap']);
            $data['nama_lengkap'] = trim($data['nama_lengkap']);
        }

        // Step 3: Extract Tempat & Tanggal Lahir
        foreach ($lines as $line) {
            // Pattern: BEKASI, 14-03-2008 or BEKASI 14/03/2008 or BEKASI, 14/03/2008
            if (preg_match('#([A-Z][A-Z\s]{2,30}?)[,\s]+(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})#i', $line, $matches)) {
                if (empty($data['tempat_lahir'])) {
                    $data['tempat_lahir'] = trim($matches[1]);
                }
                // Format tanggal: DD-MM-YYYY to YYYY-MM-DD
                $day = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                $month = str_pad($matches[3], 2, '0', STR_PAD_LEFT);
                $year = $matches[4];
                $data['tanggal_lahir'] = $year . '-' . $month . '-' . $day;
                break;
            }
        }

        // Step 4: Extract Jenis Kelamin
        foreach ($lines as $line) {
            // Pattern: Jenis Kelamin: LAKI-LAKI or Jenis Kelamin: PEREMPUAN
            if (preg_match('#Jenis\s+Kelamin[:\s]*(Laki[-\s]?laki|Perempuan|LAKI[-\s]?LAKI|PEREMPUAN|L\b|P\b)#i', $line, $matches)) {
                $jk = strtoupper(trim($matches[1]));
                if (preg_match('#^(L|LAKI)#', $jk)) {
                    $data['jenis_kelamin'] = 'Laki-laki';
                } elseif (preg_match('#^(P|PEREMPUAN)#', $jk)) {
                    $data['jenis_kelamin'] = 'Perempuan';
                }
                break;
            }
        }

        // Step 5: Extract Alamat (more robust - can be multi-line, but exclude non-address data)
        $alamatLines = [];
        $inAlamatSection = false;
        $alamatFound = false;
        
        // Keywords that indicate end of address section
        $endOfAddressKeywords = [
            'Agama', 'AGAMA', 'Religion',
            'Status', 'STATUS', 'Perkawinan', 'PERKAWINAN', 'Kawin', 'KAWIN', 'Belum', 'BELUM',
            'Pekerjaan', 'PEKERJAAN', 'Job', 'JOB',
            'Kewarganegaraan', 'KEWARGANEGARAAN', 'WNI', 'WNA',
            'Berlaku', 'BERLAKU', 'Hingga', 'HINGGA', 'Seumur', 'SEUMUR', 'Hidup', 'HIDUP',
            'Golongan', 'GOLONGAN', 'Darah', 'DARAH'
        ];
        
        // Keywords that should NOT be in address
        $excludeFromAddress = [
            'Agama', 'AGAMA', 'Religion',
            'Status', 'STATUS', 'Perkawinan', 'PERKAWINAN', 'Kawin', 'KAWIN', 'Belum', 'BELUM',
            'Pekerjaan', 'PEKERJAAN', 'Job', 'JOB',
            'Kewarganegaraan', 'KEWARGANEGARAAN', 'WNI', 'WNA',
            'Berlaku', 'BERLAKU', 'Hingga', 'HINGGA', 'Seumur', 'SEUMUR', 'Hidup', 'HIDUP',
            'Golongan', 'GOLONGAN', 'Darah', 'DARAH',
            'ISLAM', 'KRISTEN', 'KATHOLIK', 'HINDU', 'BUDHA', 'KONGHUCU',
            'BELUM KAWIN', 'KAWIN', 'CERAI', 'JANDA', 'DUDA'
        ];
        
        foreach ($lines as $index => $line) {
            $originalLine = $line;
            $line = trim($line);
            
            // Check if this line starts alamat section
            if (preg_match('#^Alamat#i', $line)) {
                $inAlamatSection = true;
                $alamatFound = true;
                // Extract alamat after "Alamat" label
                if (preg_match('#Alamat[:\s]+(.+)#i', $line, $matches)) {
                    $alamatPart = trim($matches[1]);
                    // Clean up the alamat part
                    $alamatPart = preg_replace('#^[-:\s]+#', '', $alamatPart);
                    $alamatPart = preg_replace('#[-:\s]+$#', '', $alamatPart);
                    if (strlen($alamatPart) > 3 && !$this->containsExcludedKeywords($alamatPart, $excludeFromAddress)) {
                        $alamatLines[] = $alamatPart;
                    }
                }
                continue;
            }
            
            // If in alamat section, collect lines until we hit another section
            if ($inAlamatSection) {
                // Check if this line indicates end of address section
                $isEndOfAddress = false;
                foreach ($endOfAddressKeywords as $keyword) {
                    if (preg_match('#^' . preg_quote($keyword, '#') . '#i', $line) || 
                        stripos($line, $keyword) === 0) {
                        $isEndOfAddress = true;
                        break;
                    }
                }
                
                if ($isEndOfAddress) {
                    $inAlamatSection = false;
                    break;
                }
                
                // Clean the line
                $cleanedLine = preg_replace('#^[-:\s]+#', '', $line);
                $cleanedLine = preg_replace('#[-:\s]+$#', '', $cleanedLine);
                
                // Skip if line contains excluded keywords
                if ($this->containsExcludedKeywords($cleanedLine, $excludeFromAddress)) {
                    $inAlamatSection = false;
                    break;
                }
                
                // Skip if line is a date pattern (likely tanggal lahir)
                if (preg_match('#^\d{1,2}[-\/]\d{1,2}[-\/]\d{4}#', $cleanedLine)) {
                    continue;
                }
                
                // Add line if it's substantial and doesn't contain excluded data
                if (strlen($cleanedLine) > 3) {
                    // Check for RT/RW, Kel/Desa, Kecamatan patterns (these are part of address)
                    if (preg_match('#(RT|RW|Kel|Desa|Kecamatan|Kec)[:\s/]#i', $cleanedLine) || 
                        preg_match('#^\d{3}/\d{3}#', $cleanedLine)) {
                        $alamatLines[] = $cleanedLine;
                    } elseif (strlen($cleanedLine) > 5 && !preg_match('#^\d{16}#', $cleanedLine)) {
                        // Only add if it doesn't look like excluded data
                        $alamatLines[] = $cleanedLine;
                    }
                }
            } else {
                // Try to find alamat without explicit label (but be more careful)
                if (!$alamatFound && strlen($line) > 15 && !preg_match('#^\d#', $line)) {
                    // Must not contain excluded keywords
                    if (!$this->containsExcludedKeywords($line, $excludeFromAddress) &&
                        !preg_match('#(NIK|Nama|Tempat|Tanggal|Lahir|Jenis|Kelamin|Agama|Pekerjaan|Kewarganegaraan|Berlaku)#i', $line)) {
                        // Check if it looks like an address (contains location words or RT/RW pattern)
                        if (preg_match('#(RT|RW|Kel|Desa|Kecamatan|Kec|Jalan|Jl|Gang|Gg|No|Blok|Blk)#i', $line) ||
                            preg_match('#[A-Z\s]{5,40}#', $line)) {
                            $cleanedLine = preg_replace('#^[-:\s]+#', '', $line);
                            $cleanedLine = preg_replace('#[-:\s]+$#', '', $cleanedLine);
                            $alamatLines[] = $cleanedLine;
                            $alamatFound = true;
                        }
                    }
                }
            }
        }
        
        // Combine and clean alamat lines
        if (!empty($alamatLines)) {
            $combinedAlamat = implode(' ', $alamatLines);
            // Remove excessive dashes and special characters
            $combinedAlamat = preg_replace('#\s*[-]+\s*#', ' ', $combinedAlamat);
            $combinedAlamat = preg_replace('#\s+-#', '', $combinedAlamat);
            $combinedAlamat = preg_replace('#-\s+#', '', $combinedAlamat);
            // Normalize spaces
            $combinedAlamat = preg_replace('/\s+/', ' ', $combinedAlamat);
            $combinedAlamat = trim($combinedAlamat);
            
            // Final check: remove any excluded keywords that might have slipped through
            foreach ($excludeFromAddress as $keyword) {
                $combinedAlamat = preg_replace('#\b' . preg_quote($keyword, '#') . '\b[:\s]*(.+?)(?:\s|$)#i', '', $combinedAlamat);
            }
            
            $combinedAlamat = preg_replace('/\s+/', ' ', $combinedAlamat);
            $combinedAlamat = trim($combinedAlamat);
            
            // Only set if we have meaningful content
            if (strlen($combinedAlamat) > 5) {
                $data['alamat'] = $combinedAlamat;
            }
        }

        return $data;
    }

    /**
     * Check if a line contains excluded keywords.
     *
     * @param string $line
     * @param array $excludedKeywords
     * @return bool
     */
    private function containsExcludedKeywords($line, $excludedKeywords)
    {
        foreach ($excludedKeywords as $keyword) {
            // Check if keyword appears as a label (followed by colon or space and value)
            if (preg_match('#\b' . preg_quote($keyword, '#') . '\s*[:\s]#i', $line) ||
                stripos($line, $keyword) !== false) {
                return true;
            }
        }
        return false;
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