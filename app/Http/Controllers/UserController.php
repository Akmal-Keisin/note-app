<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view("Users.index", [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("Users.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validating data
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|unique:users,email|max:255',
            'phone_number' => 'required|numeric',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'image' => 'nullable|image'
        ]);

        // storing image
        if ($request->image !== null) {
            $validatedData['image'] = $request->file('image')->store('images');
        } else {
            $validatedData['image'] = 'images/default.png';
        }

        // hashing password
        $validatedData['password'] = Hash::make($request->password);

        // create user
        User::create($validatedData);
        return redirect('mynotes-users')->with('success', 'Data Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view("Users.show", [
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view("Users.edit", [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // validating password
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => [Rule::unique('users')->ignore($id), 'nullable', 'max:255', 'email'],
            'phone_number' => 'required|numeric',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'image' => 'nullable|image'
        ]);

        // find user
        $user = User::findOrFail($id);

        // storing image
        if ($request->image !== null) {
            Storage::delete($user->image);
            $validatedData['image'] = $request->file('image')->store('images');
        } else {
            $validatedData['image'] = $user->image;
        }

        // hashing password
        $validatedData['password'] = Hash::make($request->password);

        // update user
        $user->update($validatedData);

        // return view
        return redirect('mynotes-users')->with('success', 'Data Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->image !== 'images/default.png') {
            Storage::delete($user->image);
        }
        $user->delete();
        return back()->with('success', 'Data Deleted Successfully');
    }
}
