<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GadaiMasterInapKendaraan;
use Illuminate\Http\Request;

class MasterInapKendaraanController extends Controller
{
    /**
     * Check if user has permission to CRUD master data
     */
    protected function checkCrudPermission()
    {
        if (!app(\App\Services\AdminPermissionService::class)->canCrudMasterData(auth()->user())) {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat mengelola Master Data.');
        }
    }

    public function index()
    {
        $data = GadaiMasterInapKendaraan::orderBy('golongan')->paginate(15);
        return view('admin.master-data.inap-kendaraan.index', compact('data'));
    }

    public function create()
    {
        $this->checkCrudPermission();
        return view('admin.master-data.inap-kendaraan.create');
    }

    public function store(Request $request)
    {
        $this->checkCrudPermission();
        $request->validate([
            'golongan' => 'required|string|max:10|unique:tbl_gadai_master_inap_kendaraan,golongan',
            'jenis_kendaraan' => 'required|string|max:255',
            'nominal_inap' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'golongan.required' => 'Golongan wajib diisi.',
            'golongan.unique' => 'Golongan sudah terdaftar.',
            'jenis_kendaraan.required' => 'Jenis kendaraan wajib diisi.',
            'nominal_inap.required' => 'Nominal inap wajib diisi.',
        ]);

        GadaiMasterInapKendaraan::create($request->all());

        return redirect()->route('admin.master-data.inap-kendaraan.index')
            ->with('success', 'Master Inap Kendaraan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $this->checkCrudPermission();
        $data = GadaiMasterInapKendaraan::findOrFail($id);
        return view('admin.master-data.inap-kendaraan.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $this->checkCrudPermission();
        $request->validate([
            'golongan' => 'required|string|max:10|unique:tbl_gadai_master_inap_kendaraan,golongan,' . $id,
            'jenis_kendaraan' => 'required|string|max:255',
            'nominal_inap' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'golongan.required' => 'Golongan wajib diisi.',
            'golongan.unique' => 'Golongan sudah terdaftar.',
            'jenis_kendaraan.required' => 'Jenis kendaraan wajib diisi.',
            'nominal_inap.required' => 'Nominal inap wajib diisi.',
        ]);

        $data = GadaiMasterInapKendaraan::findOrFail($id);
        $data->update($request->all());

        return redirect()->route('admin.master-data.inap-kendaraan.index')
            ->with('success', 'Master Inap Kendaraan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $this->checkCrudPermission();
        $data = GadaiMasterInapKendaraan::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.master-data.inap-kendaraan.index')
            ->with('success', 'Master Inap Kendaraan berhasil dihapus');
    }
}
