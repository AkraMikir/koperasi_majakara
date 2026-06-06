<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GadaiMasterKategori;
use App\Models\GadaiMasterInapKendaraan;
use Illuminate\Http\Request;

class MasterKategoriGadaiController extends Controller
{
    protected function checkCrudPermission()
    {
        if (!app(\App\Services\AdminPermissionService::class)->canCrudMasterData(auth()->user())) {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat mengelola Master Data.');
        }
    }

    public function index()
    {
        $data = GadaiMasterKategori::all();
        $inapKendaraans = GadaiMasterInapKendaraan::orderBy('golongan')->get();
        return view('admin.master-data.kategori-gadai.index', compact('data', 'inapKendaraans'));
    }

    public function edit($id)
    {
        $this->checkCrudPermission();
        $data = GadaiMasterKategori::findOrFail($id);
        $inapKendaraans = GadaiMasterInapKendaraan::orderBy('golongan')->get();
        return view('admin.master-data.kategori-gadai.edit', compact('data', 'inapKendaraans'));
    }

    public function update(Request $request, $id)
    {
        $this->checkCrudPermission();
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'rate_jasa' => 'required|numeric|min:0',
            'rate_denda' => 'required|numeric|min:0',
            'rate_inap_persen' => 'required|numeric|min:0',
            'masa_gadai_hari' => 'required|integer|min:1',
            'masa_tenggang_hari' => 'required|integer|min:1',
            'max_extend_default' => 'required|integer|min:0',
        ]);

        $data = GadaiMasterKategori::findOrFail($id);
        $data->update($request->all());

        // Update Master Inap Kendaraan if category is vehicle and present in request
        if ($request->has('update_inap_kendaraan') && $data->kode_kategori === 'vehicle') {
            $inapData = $request->input('inap', []);
            foreach ($inapData as $inapId => $fields) {
                $inapRecord = GadaiMasterInapKendaraan::find($inapId);
                if ($inapRecord) {
                    $inapRecord->update([
                        'jenis_kendaraan' => $fields['jenis_kendaraan'],
                        'nominal_inap' => $fields['nominal_inap'],
                    ]);
                }
            }
        }

        return redirect()->route('admin.master-data.kategori-gadai.index')
            ->with('success', 'Kategori Gadai berhasil diupdate');
    }
}
