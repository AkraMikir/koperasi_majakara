<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Label Loker - {{ $gadai->slot_kode }}</title>
        <style>
        @page { margin: 0px; }
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
    <div class="center bold" style="font-size: 16px; text-decoration: underline;">
        LABEL PENYIMPANAN BARANG
    </div>
    
    <div class="dashed"></div>
    
    <div class="slot-title">
        {{ $gadai->slot_kode }}
    </div>
    
    <div class="dashed"></div>
    
    <table>
        <tr>
            <td class="label">ID</td>
            <td>: {{ $gadai->id }}</td>
        </tr>
        <tr>
            <td class="label">Nama</td>
            <td>: {{ $gadai->nasabah->user->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Barang</td>
            <td>: {{ $gadai->item->head_1 ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Masuk</td>
            <td>: {{ \Carbon\Carbon::parse($gadai->tgl_mulai)->format('d/m/Y') }}</td>
        </tr>
    </table>
    
    <div class="dashed"></div>
    <div class="center" style="font-size: 9px;">* Tempelkan label ini pada plastik / pembungkus barang jaminan.</div>
</body>
</html>
