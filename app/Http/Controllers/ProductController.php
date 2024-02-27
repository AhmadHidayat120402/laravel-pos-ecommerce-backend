<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::orderBy('id', 'DESC')->get();
        $categories = Category::all();
        return view('admin.product.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {

        $data = $request->all();
        $data['image'] = $request->file('image')->store('products', 'public');
        Product::create($data);
        return redirect('/admin/product');
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();
            if (!empty($data['image'])) {
                $data['image'] = $request->file('image')->store('products', 'public');
            } else {
                unset($data['image']);
            }

            // Menambahkan pembaruan untuk category_id
            if(isset($data['category'])){
                $data['category_id'] = $data['category'];
                unset($data['category']);
            }

            Product::findOrFail($id)->update($data);
            return redirect('/admin/product');
        } catch (\Exception $e) {
            dd($e->getMessage()); // Tampilkan pesan error
        }
    }

    // public function update(Request $request, $id)
    // {
    //     try {
    //         $data = $request->all();
    //         // $oldImagePath = Product::findOrFail($id)->image;
    //         if (!empty($data['image'])) {
    //             $data['image'] = $request->file('image')->store('products', 'public');
    //             // Hapus gambar lama jika ada
    //             // if ($oldImagePath) {
    //             //     Storage::disk('public')->delete($oldImagePath);
    //             // }
    //         } else {
    //             unset($data['image']);
    //             // Hapus gambar lama jika ada
    //             // if ($oldImagePath) {
    //             //     Storage::disk('public')->delete($oldImagePath);
    //             // }
    //         }
    //         Product::findOrFail($id)->update($data);
    //         return redirect('/admin/product');
    //     } catch (\Exception $e) {
    //         dd($e->getMessage()); // Tampilkan pesan error
    //     }
    // }


    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return  redirect('/admin/product');
    }
}
