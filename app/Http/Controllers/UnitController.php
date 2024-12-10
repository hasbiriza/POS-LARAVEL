<?php

namespace App\Http\Controllers;

use App\Models\Units;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = units::all();
        return view('units.index', compact('units'));
    }

    public function create()
    {
        return view('units.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'nullable'
        ]);

        $unit = Units::create($validatedData);
        return redirect()->route('units.index')->with('success', 'Unit successfully added.');
    }

    public function edit($id)
    {
        $unit = Units::findOrFail($id);
        return view('units.edit', compact('unit'));
    }

    public function update(Request $request, $id)
    {
        $unit = Units::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'nullable'
        ]);

        $unit->update($validatedData);

        return redirect()->route('units.index')->with('success', 'Unit berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $unit = Units::findOrFail($id);
            $unit->delete();
            return response()->json(['success' => 'Unit berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat menghapus unit.'], 500);
        }
    }

}
