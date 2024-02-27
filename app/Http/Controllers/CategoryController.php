<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::orderBy('id', 'DESC')->get();
        return view('admin.category.index', compact('categories'));
    }


    public function store(Request $request)
    {
        $data = $request->all();
        $data['image'] = $request->file('image')->store('category', 'public');
        Category::create($data);
        return redirect('/admin/category');
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        if (!empty($data['image'])) {
            $data['image'] = $request->file('image')->store('category', 'public');
        } else {
            unset($data['image']);
        }
        Category::findOrFail($id)->update($data);
        return redirect('/admin/category');
    }

    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return  redirect('/admin/category');
    }
}
