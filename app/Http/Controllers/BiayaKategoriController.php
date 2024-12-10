<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BiayaKategori;
class BiayaKategoriController extends Controller
{
    public function index()
    {
        $biayaKategoris = BiayaKategori::all();
        return view('biaya-kategori.index', compact('biayaKategoris'));
    }

    public function create()
    {
        return view('biaya-kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        BiayaKategori::create($request->all());

        return redirect()->route('biaya-kategori.index')->with('success', 'Biaya kategori created successfully.');
    }

    public function edit($id)
    {
        $biayaKategori = BiayaKategori::find($id);
        return view('biaya-kategori.edit', compact('biayaKategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $biayaKategori = BiayaKategori::find($id);
        $biayaKategori->update($request->all());

        return redirect()->route('biaya-kategori.index')->with('success', 'Biaya kategori updated successfully.');

    }

    public function destroy($id)
    {
        $biayaKategori = BiayaKategori::find($id);
        $biayaKategori->delete();

        return response()->json(['success' => true, 'message' => 'Biaya kategori berhasil dihapus.']);
    }
}