<?php

namespace App\Http\Controllers;

use App\Models\Pajak;
use Illuminate\Http\Request;

class PajakController extends Controller
{
    public function index()
    {
        $pajak = pajak::all();
        return view('pajak.index', compact('pajak'));
    }

    public function create()
    {
        return view('pajak.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'discount_value' => 'required|numeric|min:0|max:100',
            'status' => 'required|boolean'
        ]);

        $validatedData['discount_value'] = $validatedData['discount_value'];

        $pajak = Pajak::create($validatedData);
        return redirect()->route('pajak.index')->with('success', 'Pajak berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pajak = Pajak::findOrFail($id);
        return view('pajak.edit', compact('pajak'));
    }

    public function update(Request $request, $id)
    {
        $pajak = Pajak::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'discount_value' => 'required|numeric|min:0|max:100',
            'status' => 'required|boolean'
        ]);

        $validatedData['discount_value'] = $validatedData['discount_value'];

        $pajak->update($validatedData);

        return redirect()->route('pajak.index')->with('success', 'Pajak berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $pajak = pajak::findOrFail($id);
            $pajak->delete();
            return response()->json(['success' => 'Pajak berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat menghapus pajak.'], 500);
        }
    }

}
