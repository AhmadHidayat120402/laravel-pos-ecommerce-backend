<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('admin.user.index', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['foto'] = $request->file('foto')->store('user', 'public');
        User::create($data);
        return redirect('/admin/user');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        if (!empty($data['foto'])) {
            $data['foto'] = $request->file('foto')->store('user', 'public');
        } else {
            unset($data['foto']);
        }
        User::findOrFail($id)->update($data);
        return redirect('/admin/user');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return  redirect('/admin/user');
    }
}
