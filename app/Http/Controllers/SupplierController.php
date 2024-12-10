<?php

namespace App\Http\Controllers;

use App\Models\Suppliers;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Suppliers::all();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'store_name' => 'required',
            'pic_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'nullable'
        ]);

        $supplier = Suppliers::create($validatedData);
        return redirect()->route('suppliers.index')->with('success', 'Supplier successfully added.');
    }

    public function edit($id)
    {
        $supplier = Suppliers::findOrFail($id);
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $supplier = Suppliers::findOrFail($id);

        $validatedData = $request->validate([
            'store_name' => 'required',
            'pic_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'nullable'
        ]);

        $supplier->update($validatedData);

        return redirect()->route('suppliers.index')->with('success', 'Supplier successfully updated.');
    }

    public function destroy($id)
    {
        try {
            $supplier = Suppliers::findOrFail($id);
            $supplier->delete();
            return response()->json(['success' => 'Supplier successfully deleted.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the supplier.'], 500);
        }
    }

}
