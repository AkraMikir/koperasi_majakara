<?php

namespace App\Services;

class PettyCashConstants
{
    // jns_transaksi
    const JNS_STR = 1;  // Setoran Tabungan
    const JNS_PNR = 2;  // Penarikan Tabungan  
    const JNS_PMB = 5;  // Pembayaran (Angsuran)
    const JNS_PNCR = 6; // Pencairan (Disbursement)
    
    // jns_via
    const VIA_TF   = 1; // Transfer
    const VIA_CS   = 2; // Cash
    
    // jns_fitur
    const FITUR_TABUNGAN  = 1;
    const FITUR_PINJAMAN  = 2;
    const FITUR_DEPOSITO  = 3;
    const FITUR_GADAI     = 4;
    
    // ref_table
    const REF_TABUNGAN_STR = 'tbl_pengajuan_tabungan';
    const REF_TABUNGAN_PNR = 'tbl_pengajuan_penarikan_tabungan';
    const REF_JANJI_TEMU   = 'tbl_janji_temu_tabungan';
    const REF_PINJAMAN_H   = 'tbl_pinjaman_h';
    const REF_PINJAMAN_D   = 'tbl_pengajuan_pembayaran_pinjaman';
    const REF_DEPOSITO_P   = 'tbl_pengajuan_deposito';
    const REF_GADAI_P      = 'tbl_gadai_pengajuan';
    const REF_GADAI_A      = 'tbl_gadai_active';

    // Sumber Transaksi Owner
    const SUMBER_TABUNGAN = 'tabungan';
    const SUMBER_PINJAMAN = 'pinjaman';
    const SUMBER_DEPOSITO = 'deposito';
    const SUMBER_GADAI    = 'gadai';
    const SUMBER_PETTY    = 'petty_cash';
    const SUMBER_LAIN     = 'other';
}
