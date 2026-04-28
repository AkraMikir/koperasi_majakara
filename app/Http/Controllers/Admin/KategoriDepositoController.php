<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriDeposito;

class KategoriDepositoController extends Controller
{
    // Removed constructor since middleware is not supported here in Laravel 11
    public function index()
    {
        $kategoris = KategoriDeposito::latest()->get();
        return view('admin.master-data.kategori-deposito.index', compact('kategoris'));
    }

    public function create()
    {
        return view('admin.master-data.kategori-deposito.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'keterangan'    => 'nullable|string',
            'status'        => 'required|in:aktif,nonaktif',
        ]);

        // 🛡️ Server-side guard: cegah duplikasi (double submit) dalam 10 detik
        $recentDuplicate = KategoriDeposito::where('nama_kategori', $request->nama_kategori)
            ->where('created_at', '>=', now()->subSeconds(10))
            ->exists();

        if ($recentDuplicate) {
            return redirect()->route('admin.master-data.kategori-deposito.index')
                             ->with('warning', 'Kategori tersebut sudah ditambahkan barusan. Silakan tunggu beberapa saat untuk menambahkan data yang sama.');
        }

        KategoriDeposito::create($validated);
        return redirect()->route('admin.master-data.kategori-deposito.index')
                         ->with('success', 'Kategori Deposito berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kategori = KategoriDeposito::findOrFail($id);
        return view('admin.master-data.kategori-deposito.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $kategori = KategoriDeposito::findOrFail($id);
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'keterangan'    => 'nullable|string',
            'status'        => 'required|in:aktif,nonaktif',
        ]);

        $kategori->update($validated);
        return redirect()->route('admin.master-data.kategori-deposito.index')
                         ->with('success', 'Kategori Deposito berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kategori = KategoriDeposito::findOrFail($id);
        $kategori->update(['status' => 'nonaktif']);
        return redirect()->route('admin.master-data.kategori-deposito.index')
                         ->with('success', 'Kategori Deposito berhasil dinonaktifkan.');
    }
}
