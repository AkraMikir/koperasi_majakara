<?php

namespace App\Helpers;

class CurrencyHelper
{
    /**
     * Format large numbers into Indonesian readable words (T, M, Jt).
     * Example: 1.500.000.000 => 1,5 Miliar
     */
    public static function formatBerbilang($nominal)
    {
        $nominal = (float) $nominal;
        
        if ($nominal >= 1000000000000) {
            $triliun = floor($nominal / 1000000000000);
            $sisa = $nominal % 1000000000000;
            $miliar = floor($sisa / 1000000000);
            
            $text = number_format($triliun, 0, ',', '.') . ' Triliun';
            if ($miliar > 0) {
                $text .= ' ' . number_format($miliar, 0, ',', '.') . ' Miliar';
            }
            return $text;
        }
        
        if ($nominal >= 1000000000) {
            $miliar = floor($nominal / 1000000000);
            $sisa = $nominal % 1000000000;
            $juta = floor($sisa / 1000000);
            
            $text = number_format($miliar, 0, ',', '.') . ' Miliar';
            if ($juta > 0) {
                $text .= ' ' . number_format($juta, 0, ',', '.') . ' Juta';
            }
            return $text;
        }
        
        if ($nominal >= 1000000) {
            $juta = floor($nominal / 1000000);
            $sisa = $nominal % 1000000;
            $ribu = floor($sisa / 1000);
            
            $text = number_format($juta, 0, ',', '.') . ' Juta';
            if ($ribu > 0) {
                // Ribu is usually too small for main cards, but user wants detail
                $text .= ' ' . number_format($ribu, 0, ',', '.') . ' Rb';
            }
            return $text;
        }
        
        return number_format($nominal, 0, ',', '.');
    }

    public static function formatShort($nominal)
    {
        $nominal = (float) $nominal;
        if ($nominal >= 1000000000000) {
            return number_format($nominal / 1000000000000, 2, ',', '.') . ' T';
        }
        if ($nominal >= 1000000000) {
            return number_format($nominal / 1000000000, 2, ',', '.') . ' M';
        }
        if ($nominal >= 1000000) {
            return number_format($nominal / 1000000, 1, ',', '.') . ' Jt';
        }
        return number_format($nominal, 0, ',', '.');
    }
}
