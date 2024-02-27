<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
        $users = User::orderBy('id', 'DESC')->get();
        return view('admin.user.index', compact('users'));
    }


    public function store(Request $request)
    {
        $data = $request->all();
        $data['foto'] = $request->file('foto')->store('user', 'public');
        User::create($data);
        return redirect('/admin/user');
    }

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

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return  redirect('/admin/user');
    }
}
