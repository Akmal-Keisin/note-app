<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $data = User::all();
        return view('Users.index', [
            'users' => $data
        ]);
    }

    public function create()
    {
        return view('Users.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|numeric|unique:users,phone_number',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'image' => 'nullable|image'
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        if ($request->hasFile("image") && $request->image != null) {
            $data['image'] = env('APP_URL') . '/' . $request->file('image')->store('images');
        }

        User::create($data);
        return redirect('/mynotes-users')->with('success', 'Data Created Successfully');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('Users.show', [
            'user' => $user
        ]);
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view("Users.edit", [
            'user' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        // Find User
        $user = User::find($id);
        // Check user exist or not
        if ($user) {

            $user->name = ($request->name) ? $request->name : $user->name;
            $user->email = ($request->email) ? $request->email : $user->email;
            $user->phone_number = ($request->phone_number) ? $request->phone_number : $user->phone_number;

            // Check is user set image or not
            if ($request->hasFile('image') && $request->image != null) {
                if (!str_contains($user->image, 'images')) {
                    $image = explode(env("APP_URL") . '/', $user->image);
                    $user->image = env("APP_URL") . '/images/' . $image[1];
                }
                $image = explode(env("APP_URL") . '/', $user->image);
                Storage::delete($image[1]);
                $user->image = env("APP_URL") . '/' . $request->file('image')->store('images');
            } else {
                if (!str_contains($user->image, 'images')) {
                    $image = explode(env("APP_URL") . '/', $user->image);
                    $user->image = env("APP_URL") . '/images/' . $image[1];
                }
                $user->image = $user->image;
            }

            // Hashing password
            $user->password = ($request->password) ? Hash::make($request->password) : $user->password;
            $user->update();

            // Returning api
            return redirect("/mynotes-users")->with("success", "Data Updated Successfully");
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->image && $user->image != null && str_contains($user->image, env('APP_URL'))) {
            $image = explode(env("APP_URL") . '/', $user->image);
            Storage::delete($image[1]);
        } else {
            Storage::delete($user->image);
        }
        Note::where('user_id', $id)->delete();
        $user->delete();
        return back()->with("success", "Data Deleted Successfully");
    }
}
