<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Units;
use App\Models\Brands;
use App\Models\Categories;
use App\Models\Stores;
use App\Models\ProductPricing;
use App\Models\ProductImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $store = Stores::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();
        $storeIds = $store->pluck('id')->toArray();
        $products = Product::getProductsWithDetails($storeIds);
        return view('products.index', compact('products'));
    }
    public function show($id)
    {
        $product = Product::with(['variants' => function($query) {
            $query->with(['images', 'store']);
        }, 'categories', 'brand', 'unit'])->findOrFail($id);
        return view('products.show', compact('product'))->render();
    }

    public function create()
    {
        $units = Units::all();
        $brands = Brands::all();
        $categories = Categories::all();
        $stores = Stores::all();
        return view('products.create', compact('units', 'brands', 'categories', 'stores'));
    }
    
    public function store(Request $request)
    {
        if ($request->has_variant == 'on') {
            $validatedData = $request->validate([
                'name' => 'required',
                'description' => 'required',
                'unit_id' => 'required|exists:units,id',
                'brand_id' => 'required|exists:brands,id',
                'category_id' => 'required|array',
                'category_id.*' => 'exists:categories,id',
                'store_id' => 'required|exists:stores,id',
                'variants.*.variasi_1' => 'nullable|string',
                'variants.*.variasi_2' => 'nullable|string',
                'variants.*.variasi_3' => 'nullable|string',
                'variants.*.purchase_price' => 'required|numeric',
                'variants.*.sale_price' => 'required|numeric',
                'variants.*.stock' => 'required|integer',
                'variants.*.sku' => 'required|string',
                'variants.*.weight' => 'nullable|numeric',
                'variants.*.barcode' => 'required|string',
                'variants.*.image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
        } else {
            $validatedData = $request->validate([
                'name' => 'required',
                'description' => 'required',
                'unit_id' => 'required|exists:units,id',
                'brand_id' => 'required|exists:brands,id',
                'category_id' => 'required|array',
                'category_id.*' => 'exists:categories,id',
                'store_id' => 'required|exists:stores,id',
                'purchase_price' => 'required|numeric',
                'sale_price' => 'required|numeric',
                'stock' => 'required|integer',
                'sku' => 'required|string',
                'weight' => 'nullable|numeric',
                'barcode' => 'required|string',
                'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
        }
        
        $product = Product::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'unit_id' => $validatedData['unit_id'],
            'brand_id' => $validatedData['brand_id'],
            'has_varian' => $request->has_variant == 'on' ? 'Y' : 'N'
        ]);

        $product->categories()->attach($validatedData['category_id']);

        if($request->has_variant == 'on'){
            foreach ($validatedData['variants'] as $key => $variant) {
                $productVariant = $product->variants()->create([
                    'variasi_1' => $variant['variasi_1'] ?? null,
                    'variasi_2' => $variant['variasi_2'] ?? null,
                    'variasi_3' => $variant['variasi_3'] ?? null,
                    'purchase_price' => $variant['purchase_price'],
                    'sale_price' => $variant['sale_price'],
                    'stock' => $variant['stock'],
                    'store_id' => $validatedData['store_id'],
                    'sku' => $variant['sku'] ?? null,
                    'weight' => $variant['weight'] ?? null,
                    'barcode' => $variant['barcode'] ?? null,
                ]);  
                
                if ($request->hasFile("variants.$key.images")) {
                    foreach ($request->file("variants.$key.images") as $image) {
                        $imagePath = $image->store('product_images', 'public');
                        $productVariant->images()->create([
                            'image_url' => $imagePath
                        ]);
                    }
                }
            }

        } else {
            $productVariant  = $product->variants()->create([
                'variasi_1' => null,
                'variasi_2' => null,
                'variasi_3' => null,
                'purchase_price' => $validatedData['purchase_price'],
                'sale_price' => $validatedData['sale_price'],
                'stock' => $validatedData['stock'],
                'weight' => $validatedData['weight'] ?? null,
                'store_id' => $validatedData['store_id'],
                'sku' => $validatedData['sku'] ?? null,
                'barcode' => $validatedData['barcode'] ?? null,
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePath = $image->store('product_images', 'public');
                    $productVariant->images()->create([
                        'image_url' => $imagePath
                    ]);
                }
            }

        }

        return redirect()->route('products.index')->with('success', 'Product added successfully.');
    }

    function edit($id)
    {
        $product = Product::with(['variants' => function($query) {
            $query->with(['images', 'store']);
        }, 'categories', 'brand', 'unit'])->findOrFail($id);

        if (!$product) {
            abort(404);
        }

        $categories = Categories::all();
        $brands = Brands::all();
        $units = Units::all();
        $stores = Stores::all();

        return view('products.edit', compact('product', 'categories', 'brands', 'units', 'stores'));
    }

    function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'brand_id' => 'required|exists:brands,id',
            'unit_id' => 'required|exists:units,id',
            'category_id' => 'required|array',
            'category_id.*' => 'exists:categories,id',
            'has_variant' => 'nullable|in:Y,N',
            'store_id' => 'required|exists:stores,id',
        ]);

        $validatedData['has_variant'] = $request->has('has_variant') ? 'Y' : 'N'; 
        $product = Product::findOrFail($id);
        $product->update([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'brand_id' => $validatedData['brand_id'],
            'unit_id' => $validatedData['unit_id'],
            'has_varian' => $validatedData['has_variant']
        ]);        
        $product->categories()->sync($validatedData['category_id']);
        if ($validatedData['has_variant'] == 'N') {
            $variantData = $request->validate([
                'variants.0.purchase_price' => 'required|numeric|min:0',
                'variants.0.sale_price' => 'required|numeric|min:0',
                'variants.0.stock' => 'required|integer|min:0',
                'variants.0.weight' => 'nullable|numeric|min:0',
                'variants.0.sku' => 'nullable|string|max:255',
                'variants.0.barcode' => 'nullable|string|max:255',
            ]);

            $variant = $product->variants()->updateOrCreate(
                ['id' => $product->variants->first()->id ?? null],
                [
                    'store_id' => $validatedData['store_id'],
                    'variasi_1' => null,
                    'variasi_2' => null,
                    'variasi_3' => null,
                    'purchase_price' => $variantData['variants'][0]['purchase_price'],
                    'sale_price' => $variantData['variants'][0]['sale_price'],
                    'stock' => $variantData['variants'][0]['stock'],
                    'weight' => $variantData['variants'][0]['weight'],
                    'sku' => $variantData['variants'][0]['sku'],
                    'barcode' => $variantData['variants'][0]['barcode'],
                ]
            );

            if ($request->hasFile('variants.0.images')) {
                foreach ($request->file('variants.0.images') as $image) {
                    $imagePath = $image->store('product_images', 'public');
                    $variant->images()->create([
                        'image_url' => $imagePath
                    ]);
                }
            }

            $product->variants()->where('id', '!=', $variant->id)->delete();
        } else {
            $variantData = $request->validate([
                'variants.*.id' => 'nullable|exists:product_pricing,id',
                'variants.*.variasi_1' => 'required|string|max:255',
                'variants.*.variasi_2' => 'nullable|string|max:255',
                'variants.*.variasi_3' => 'nullable|string|max:255',
                'variants.*.purchase_price' => 'required|numeric|min:0',
                'variants.*.sale_price' => 'required|numeric|min:0',
                'variants.*.stock' => 'required|integer|min:0',
                'variants.*.weight' => 'nullable|numeric|min:0',
                'variants.*.sku' => 'nullable|string|max:255',
                'variants.*.barcode' => 'nullable|string|max:255',
                'variants.*.images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $existingVariantIds = $product->variants->pluck('id')->toArray();
            $updatedVariantIds = [];
            
            foreach ($variantData['variants'] as $index => $variantInput) {
                $variantDetails = [
                    'store_id' => $validatedData['store_id'],
                    'variasi_1' => $variantInput['variasi_1'],
                    'variasi_2' => $variantInput['variasi_2'],
                    'variasi_3' => $variantInput['variasi_3'],
                    'purchase_price' => $variantInput['purchase_price'],
                    'sale_price' => $variantInput['sale_price'],
                    'stock' => $variantInput['stock'],
                    'weight' => $variantInput['weight'],
                    'sku' => $variantInput['sku'],
                    'barcode' => $variantInput['barcode'],
                ];
            
                $variant = $product->variants()->updateOrCreate(
                    ['id' => $variantInput['id'] ?? null],
                    $variantDetails
                );
                $updatedVariantIds[] = $variant->id;
            
                if ($request->hasFile("variants.{$index}.images")) {
                    foreach ($request->file("variants.{$index}.images") as $image) {
                        $imagePath = $image->store('product_images', 'public');
                        $variant->images()->create([
                            'image_url' => $imagePath
                        ]);
                    }
                }
            }
            $variantsToDelete = array_diff($existingVariantIds, $updatedVariantIds);
            $product->variants()->whereIn('id', $variantsToDelete)->delete();
        }
        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            foreach ($product->variants as $variant) {
                foreach ($variant->images as $image) {
                    Storage::disk('public')->delete($image->image_url);
                    $image->delete();
                }
                $variant->delete();
            }
            
            $product->delete();
            
            return response()->json(['message' => 'Produk berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus produk: ' . $e->getMessage()], 500);
        }
    }

    public function deleteImage($id)
    {
        $image = ProductImages::findOrFail($id);
        Storage::disk('public')->delete($image->image_url);
        $image->delete();
        return response()->json(['message' => 'Gambar berhasil dihapus'], 200);
    }
}
