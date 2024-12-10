<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Request;

class   CustomerController extends Controller
{
    public function index()
    {
        $customers = Customers::all();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'nullable'
        ]);

        $customer = Customers::create($validatedData);
        return redirect()->route('customers.index')->with('success', 'Customer successfully added.');
    }

    public function edit($id)
    {
        $customer = Customers::findOrFail($id);
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customers::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'nullable'
        ]);

        $customer->update($validatedData);

        return redirect()->route('customers.index')->with('success', 'Customer successfully updated.');
    }

    public function destroy($id)
    {
        try {
            $customer = Customers::findOrFail($id);
            $customer->delete();
            return response()->json(['success' => 'Customer successfully deleted.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the customer.'], 500);
        }
    }

}
