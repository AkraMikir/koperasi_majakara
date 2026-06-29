<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GadaiMasterItem;
use App\Models\GadaiMasterKategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MasterItemGadaiController extends Controller
{
    /**
     * Check if user has permission to CRUD master data
     */
    protected function checkCrudPermission()
    {
        if (!app(\App\Services\AdminPermissionService::class)->canCrudItemGadai(auth()->user())) {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama dan Admin Operasional yang dapat mengelola Item Gadai.');
        }
    }

    public function index()
    {
        $data = GadaiMasterItem::with('kategori')->paginate(15);
        return view('admin.master-data.item-gadai.index', compact('data'));
    }

    public function create()
    {
        $this->checkCrudPermission();
        $kategoris = GadaiMasterKategori::all();
        $inapKendaraans = \App\Models\GadaiMasterInapKendaraan::all();
        return view('admin.master-data.item-gadai.create', compact('kategoris', 'inapKendaraans'));
    }

    public function store(Request $request)
    {
        $this->checkCrudPermission();
        $request->validate([
            'kategori_id' => 'required|exists:tbl_gadai_master_kategori,id',
            'head_1' => 'required|string|max:255',
            'head_2' => 'nullable|string|max:255',
            'file_pic' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10000',
            'nominal_real' => 'required|numeric|min:0',
            'bunga_low' => 'required|numeric|min:0',
            'nominal_low' => 'required|numeric|min:0',
            'bunga_high' => 'required|numeric|min:0',
            'nominal_high' => 'required|numeric|min:0',
            'nominal_inap' => 'nullable|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        $data = $request->except('file_pic');

        if ($request->hasFile('file_pic')) {
            $path = $request->file('file_pic')->store('master-item', 'public');
            $data['file_pic'] = $path;
        }

        GadaiMasterItem::create($data);

        return redirect()->route('admin.master-data.item-gadai.index')
            ->with('success', 'Item Gadai berhasil ditambahkan');
    }

    public function edit($id)
    {
        $this->checkCrudPermission();
        $data = GadaiMasterItem::findOrFail($id);
        $kategoris = GadaiMasterKategori::all();
        $inapKendaraans = \App\Models\GadaiMasterInapKendaraan::all();
        return view('admin.master-data.item-gadai.edit', compact('data', 'kategoris', 'inapKendaraans'));
    }

    public function update(Request $request, $id)
    {
        $this->checkCrudPermission();
        $request->validate([
            'kategori_id' => 'required|exists:tbl_gadai_master_kategori,id',
            'head_1' => 'required|string|max:255',
            'head_2' => 'nullable|string|max:255',
            'file_pic' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10000',
            'nominal_real' => 'required|numeric|min:0',
            'bunga_low' => 'required|numeric|min:0',
            'nominal_low' => 'required|numeric|min:0',
            'bunga_high' => 'required|numeric|min:0',
            'nominal_high' => 'required|numeric|min:0',
            'nominal_inap' => 'nullable|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        $data = GadaiMasterItem::findOrFail($id);
        $updateData = $request->except('file_pic');

        if ($request->hasFile('file_pic')) {
            // Hapus foto lama jika ada
            if ($data->file_pic) {
                Storage::disk('public')->delete($data->file_pic);
            }
            $path = $request->file('file_pic')->store('master-item', 'public');
            $updateData['file_pic'] = $path;
        }

        $data->update($updateData);

        return redirect()->route('admin.master-data.item-gadai.index')
            ->with('success', 'Item Gadai berhasil diupdate');
    }

    public function destroy($id)
    {
        $this->checkCrudPermission();
        $data = GadaiMasterItem::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.master-data.item-gadai.index')
            ->with('success', 'Item Gadai berhasil dihapus');
    }
}
