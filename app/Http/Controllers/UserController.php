<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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

    public function show()
    {
        try {
            return response()->json([
                'status' => 200,
                'info' => 'Data Obtained Successfully',
                'data' => Auth::user()
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'info' => 'Internal Server Error',
                'data' => $e->errorInfo
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            // Find User
            $user = User::find(Auth::user()->id);
            // Check user exist or not
            if ($user) {

                $user->name = ($request->name) ? $request->name : Auth::user()->name;
                $user->email = ($request->email) ? $request->email : Auth::user()->email;
                $user->phone_number = ($request->phone_number) ? $request->phone_number : Auth::user()->phone_number;

                // Check is user set image or not
                if ($request->hasFile('image')) {
                    if (!str_contains(Auth::user()->image, 'images')) {
                        $image = explode('https://magang.crocodic.net/ki/kelompok_3/note-backend/public/', Auth::user()->image);
                        Auth::user()->image = 'https://magang.crocodic.net/ki/kelompok_3/note-backend/public/images/' . $image[1];
                    }
                    $image = explode('https://magang.crocodic.net/ki/kelompok_3/note-backend/public/', Auth::user()->image);
                    Storage::delete($image[1]);
                    $user->image = 'https://magang.crocodic.net/ki/kelompok_3/note-backend/public/' . $request->file('image')->store('images');
                } else {
                    if (!str_contains(Auth::user()->image, 'images')) {
                        $image = explode('https://magang.crocodic.net/ki/kelompok_3/note-backend/public/', Auth::user()->image);
                        Auth::user()->image = 'https://magang.crocodic.net/ki/kelompok_3/note-backend/public/images/' . $image[1];
                    }
                    $user->image = Auth::user()->image;
                }

                // Hashing password
                $user->password = ($request->password) ? Hash::make($request->password) : Auth::user()->password;
                $user->update();

                // Returning api
                return response()->json([
                    'status' => 200,
                    'info' => 'Data Updated Successfully',
                    'data' => $user
                ], 200);
            }
            return response()->json([
                'status' => 404,
                'info' => 'Data Not Found',
                'data' => $user
            ], 404);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'info' => 'Internal Server Error',
                'data' => $e->errorInfo
            ], 500);
        }
    }
}
