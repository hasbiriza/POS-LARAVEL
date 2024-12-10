<?php

namespace App\Http\Controllers;

use App\Models\Stores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Stores::all();
        return view('stores.index', compact('stores'));
    }

    public function create()
    {
        return view('stores.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'store_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('store_logos', 'public');
            $validatedData['logo'] = $logoPath;
        }
        $store = Stores::create($validatedData);
        return redirect()->route('stores.index')->with('success', 'Store successfully added.');
    }

    public function edit($id)
    {
        $store = Stores::findOrFail($id);
        return view('stores.edit', compact('store'));
    }

    public function update(Request $request, $id)
    {
        $store = Stores::findOrFail($id);
        $validatedData = $request->validate([
            'store_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($store->logo) {
                Storage::disk('public')->delete($store->logo);
            }
            $logoPath = $request->file('logo')->store('store_logos', 'public');
            $validatedData['logo'] = $logoPath;
        }
        $store->update($validatedData);

        return redirect()->route('stores.index')->with('success', 'Store successfully updated.');
    }
    public function destroy($id)
    {
        try {
            $store = Stores::findOrFail($id);
            if ($store->logo) {
                Storage::disk('public')->delete($store->logo);
            }
            $store->delete();
            return response()->json(['success' => 'Store successfully deleted.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the menu.'], 500);
        }
    }

}
