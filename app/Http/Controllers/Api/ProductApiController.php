<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::when($request->category_id, function ($query) use ($request) {
            return $query->where('category_id', $request->category_id);
        })->get();
        $products->load('category');
        return response()->json([
            'status' => 'Success',
            'data' => $products
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Sesuaikan dengan kebutuhan Anda
            'is_available' => 'required|boolean',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id' // Pastikan category_id yang diberikan sesuai dengan ID yang ada di tabel categories
        ]);

        $imagePath = $request->file('image')->store('products', 'public');

        $product = Product::create([
            'name' => $validatedData['name'],
            'price' => $validatedData['price'],
            'stock' => $validatedData['stock'],
            'image' => $imagePath,
            'is_available' => $validatedData['is_available'],
            'description' => $validatedData['description'],
            'category_id' => $validatedData['category_id']
        ]);

        if ($product) {
            return response()->json([
                'success' => true,
                'message' => 'Product Created',
                'data' => $product
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product failed to Save'
            ], 409);
        }
    }



    // $request->validate(
    //     [
    //         'name' => 'required|min:3',
    //         'price' => 'required|integer',
    //         'stock' => 'required|integer',
    //         'image' => 'required|image|mimes:png,jpg,jpeg',
    //         'is_available' => 'required|integer',
    //         'description' => '',
    //         'category_id' => 'required|integer'
    //     ]
    // );
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        return response()->json([
            'message' => 'Success',
            'data' => $product
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->all();

        if (!empty($data['image'])) {
            $data['image'] = $request->file('image')->store('products', 'public');
        } else {
            unset($data['image']);
        }

        $product->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Product Updated',
            'data' => $product
        ]);
    }





    /**
     * Remove the specified resource from storage.
     */
    public function destroyProduct($id)
    {
        Product::findOrFail($id)->delete();
        return response()->json([
            'seccess' => true,
            'message' => 'Product Deleted',
        ]);
    }
}
