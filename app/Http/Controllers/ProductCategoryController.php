<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categoriesTree = Categories::with('children')
            ->whereNull('parent_id')
            ->orderBy('id')
            ->get();
// dd($categoriesTree->toArray());
        return view('categories.index', compact('categoriesTree'));
    }

    public function create()
    {
        $parentCategories = Categories::whereNull('parent_id')->get();
        return view('categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        if ($request->filled('parent_id')) {
            $validatedData['parent_id'] = $request->input('parent_id');
        } else {
            $validatedData['parent_id'] = null;
        }

        $category = Categories::create($validatedData);
        return redirect()->route('categories.index')->with('success', 'Category added successfully.');
    }

    public function edit($id)
    {
        $category = Categories::findOrFail($id);
        $parentCategories = Categories::whereNull('parent_id')->where('id', '!=', $id)->get();
        return view('categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, $id)
    {
        $category = Categories::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        if ($request->filled('parent_id')) {
            $validatedData['parent_id'] = $request->input('parent_id');
        } else {
            $validatedData['parent_id'] = null;
        }

        $category->update($validatedData);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $category = Categories::findOrFail($id);
            $category->delete();
            return response()->json(['success' => 'Category deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the category.'], 500);
        }
    }

}
