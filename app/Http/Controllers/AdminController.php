<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admins = Admin::all();
        return view('Admins.index', [
            'admins' => $admins
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Admins.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validating request
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'image' => 'nullable|image',
            'email' => 'required|max:255|unique:admins,email',
            'password' => 'required'
        ]);

        // storing image
        if ($request->image !== null) {
            $validatedData['image'] = 'https://magang.crocodic.net/ki/kelompok_3/note-backend/public/' . $request->file('image')->store('images');
        } else {
            $validatedData['image'] = 'https://magang.crocodic.net/ki/kelompok_3/note-backend/public/images/default.png';
        }

        // hashing password
        $validatedData['password'] = Hash::make($request->password);

        // create admin
        Admin::create($validatedData);

        // redirect to view
        return redirect('mynotes-admins')->with('success', 'Data Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $admin = Admin::findOrFail($id);
        return view('Admins.show', [
            'admin' => $admin
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        return view('Admins.edit', [
            'admin' => $admin
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // find admin
        $admin = Admin::findOrFail($id);

        // validating request
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'image' => 'nullable|image',
            'email' => [Rule::unique('admins')->ignore($id), 'nullable', 'max:255', 'email'],
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]);

        // storing image
        if ($request->image !== null) {
	    $image = explode('https://magang.crocodic.net/ki/kelompok_3/note-backend/public/', $admin->image);
	    Storage::delete($image);
            $validatedData['image'] = 'https://magang.crocodic.net/ki/kelompok_3/note-backend/public/' . $request->file('image')->store('images');
        } else {
            $validatedData['image'] = $admin->image;
        }

        // hashing password
        $validatedData['password'] = Hash::make($request->password);

        // update admin
        $admin->update($validatedData);

        // redirect to view
        return redirect('mynotes-admins')->with('success', 'Data Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // find admin
        $admin = Admin::findOrFail($id);

        // deleting image
        if ($admin->image !== 'images/default.png') {
	    $image = explode('https://magang.crocodic.net/ki/kelompok_3/note-backend/public/', $admin);
            Storage::delete($image);
        }

        // deleting admin
        $admin->delete();

        // redirect back to view
        return back()->with('success', 'Data Deleted Successfully');
    }
}
