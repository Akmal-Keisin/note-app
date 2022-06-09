<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserApiController extends Controller
{
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
                    Storage::delete(Auth::user()->image);
                    $user->image = $request->file('image')->store('images');
                } else {
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
