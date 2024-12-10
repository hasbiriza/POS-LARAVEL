<?php

namespace App\Http\Controllers;

use App\Models\Brands;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index()
    {
        $brands = brands::all();
        return view('brands.index', compact('brands'));
    }

    public function create()
    {
        return view('brands.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('brand_logos', 'public');
            $validatedData['logo'] = $logoPath;
        }
        $brand = brands::create($validatedData);
        return redirect()->route('brands.index')->with('success', 'Merek berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $brand = brands::findOrFail($id);
        return view('brands.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $brand = brands::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($request->hasFile('logo')) {
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            $logoPath = $request->file('logo')->store('brand_logos', 'public');
            $validatedData['logo'] = $logoPath;
        }
        $brand->update($validatedData);
        return redirect()->route('brands.index')->with('success', 'Merek berhasil diperbarui.');
    }
    public function destroy($id)
    {
        try {
            $brand = brands::findOrFail($id);
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            $brand->delete();
            return response()->json(['success' => 'Merek berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat menghapus merek.'], 500);
        }
    }

}
