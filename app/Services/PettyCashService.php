<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * PettyCashService
 *
 * DB-driven replacement for hardcoded PettyCashConstants.
 * All lookups are cached for 1 hour to avoid N+1 DB queries.
 */
class PettyCashService
{
    // ──────────────────────────────────────────────────
    //  jns_transaksi
    // ──────────────────────────────────────────────────

    /**
     * Get ID from jns_transaksi by kode.
     * @param string $kode  e.g. 'STR', 'PNR', 'PMB', 'PNCR'
     * @return int
     * @throws \Exception if kode not found
     */
    public static function getJnsId(string $kode): int
    {
        $id = Cache::remember("petty_jns_{$kode}", 3600, function () use ($kode) {
            return DB::table('jns_transaksi')->where('kode', $kode)->value('id');
        });

        if (!$id) {
            throw new \Exception("Jenis transaksi '{$kode}' tidak ditemukan di database.");
        }

        return (int) $id;
    }

    // ──────────────────────────────────────────────────
    //  jns_via
    // ──────────────────────────────────────────────────

    /**
     * Get ID from jns_via by kode.
     * @param string $kode  e.g. 'TF', 'CS', 'TN'
     * @return int
     * @throws \Exception if kode not found
     */
    public static function getViaId(string $kode): int
    {
        $id = Cache::remember("petty_via_{$kode}", 3600, function () use ($kode) {
            return DB::table('jns_via')->where('kode', $kode)->value('id');
        });

        if (!$id) {
            throw new \Exception("Jenis via '{$kode}' tidak ditemukan di database.");
        }

        return (int) $id;
    }

    // ──────────────────────────────────────────────────
    //  jns_fitur
    // ──────────────────────────────────────────────────

    /**
     * Get ID from jns_fitur by kode.
     * @param string $kode  e.g. 'T' (Tabungan), 'P' (Pinjaman)
     * @return int
     * @throws \Exception if kode not found
     */
    public static function getFiturId(string $kode): int
    {
        $id = Cache::remember("petty_fitur_{$kode}", 3600, function () use ($kode) {
            return DB::table('jns_fitur')->where('kode', $kode)->value('id');
        });

        if (!$id) {
            throw new \Exception("Jenis fitur '{$kode}' tidak ditemukan di database.");
        }

        return (int) $id;
    }

    // ──────────────────────────────────────────────────
    //  Convenience shortcuts (matching PettyCashConstants API)
    // ──────────────────────────────────────────────────

    public static function jnsStr():  int { return static::getJnsId('STR'); }
    public static function jnsPnr():  int { return static::getJnsId('PNR'); }
    public static function jnsPmb():  int { return static::getJnsId('PMB'); }
    public static function jnsPncr(): int { return static::getJnsId('PNCR'); }

    public static function viaTf(): int { return static::getViaId('TF'); }
    public static function viaCs(): int { return static::getViaId('CS'); }

    public static function fiturTabungan(): int { return static::getFiturId('T'); }
    public static function fiturPinjaman(): int { return static::getFiturId('P'); }

    // ──────────────────────────────────────────────────
    //  Cache busting (call when jns_* master data changes)
    // ──────────────────────────────────────────────────

    public static function clearCache(): void
    {
        foreach (['STR', 'PNR', 'PMB', 'PNCR'] as $k) {
            Cache::forget("petty_jns_{$k}");
        }
        foreach (['TF', 'CS', 'TN'] as $k) {
            Cache::forget("petty_via_{$k}");
        }
        foreach (['T', 'P', 'D', 'G'] as $k) {
            Cache::forget("petty_fitur_{$k}");
        }
    }
}
