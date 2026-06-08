<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Syarat & Ketentuan Gadai - {{ $gadai->slot_kode }}</title>
        <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier', monospace; font-size: 12px; line-height: 1.5; color: #000; padding: 4px; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        .dashed { border-top: 1px dashed #000; margin: 9px 0; }
        .table-row { width: 100%; margin-bottom: 3px; }
        .table-row td { vertical-align: top; }
        .label { font-weight: bold; width: 45%; }
        .header { margin-bottom: 12px; }
        .footer { margin-top: 12px; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 1px 0; }
        .text-right { text-align: right; }
        .signature-box { width: 100%; margin-top: 20px; }
        .signature-box td { text-align: center; font-size: 10px; height: 55px; vertical-align: bottom; }
        .syarat { font-size: 10px; margin-top: 6px; text-align: justify; line-height: 1.2; white-space: pre-line; }
        .nominal-table td { padding: 2px 0; }
        .slot-title { font-size: 32px; font-weight: bold; margin: 10px 0; text-align: center; }
        .approver { margin-top: 10px; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="center bold underline" style="font-size: 14px; margin-bottom: 2px;">
            {{ $settings->nama_koperasi }}
        </div>
        <div class="center" style="font-size: 10px;">
            {{ $settings->alamat_koperasi }}<br>
            Telp: {{ $settings->no_telp }}
        </div>
    </div>
    
    <div class="center bold" style="margin-bottom: 9px; font-size: 13px;">
        SYARAT & KETENTUAN GADAI
    </div>
    
    <div class="dashed"></div>
    
    <div class="syarat">
        {{ $settings->syarat_ketentuan_gadai }}
    </div>
    
    <div class="dashed"></div>
    
    <div class="footer center">
        <div>{{ $settings->nama_pt }}</div>
        <div class="bold" style="margin-top: 4px;">Harap Simpan Lembar Ini</div>
    </div>
</body>
</html>
