<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BankController extends Controller
{
    public function index()
    {
        $bank = Bank::masterbank();
        return view('bank.index', compact('bank'));
    }

    public function create()
    {
        return view('bank.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'branch' => 'required',
            'account_number' => 'required',
            'account_name' => 'required',
            'swift_code' => 'nullable',
            'credit' => 'required|numeric|min:0',
            'debit' => 'required|numeric|min:0',
            'saldo' => 'required|numeric',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('bank_logos', 'public');
            $validatedData['logo'] = $logoPath;
        }

        $bank = Bank::create($validatedData);
        return redirect()->route('bank.index')->with('success', 'Bank berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $bank = Bank::findOrFail($id);
        return view('bank.edit', compact('bank'));
    }

    public function update(Request $request, $id)
    {
        $bank = Bank::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required',
            'branch' => 'required',
            'account_number' => 'required',
            'account_name' => 'required',
            'swift_code' => 'nullable',
            'credit' => 'required|numeric|min:0',
            'debit' => 'required|numeric|min:0',
            'saldo' => 'required|numeric',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('logo')) {
            if ($bank->logo) {
                Storage::disk('public')->delete($bank->logo);
            }
            $logoPath = $request->file('logo')->store('bank_logos', 'public');
            $validatedData['logo'] = $logoPath;
        }

        $bank->update($validatedData);

        return redirect()->route('bank.index')->with('success', 'Bank berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $bank = Bank::findOrFail($id);
            if ($bank->logo) {
                Storage::disk('public')->delete($bank->logo);
            }
            $bank->delete();
            return response()->json(['success' => 'Bank berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat menghapus bank.'], 500);
        }
    }
}
