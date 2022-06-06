<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthApiController extends Controller
{
    public function authRegister(Request $request)
    {
        $credentials = Validator::make($request->all(), [
            'email' => 'required|max:255|unique:users,email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'name' => 'required|max:255',
            'phone_number' => 'required|numeric',
            'image' => 'nullable|image'
        ]);

        if ($credentials->fails()) {
            return response()->json([
                'status' => 400,
                'info' => 'Validation Failed',
                'data' => $credentials->errors()
            ]);
        }

        try {
            if ($request->hasFile('image')) {
                $credentials['image'] = Storage::disk('public')->put($request->file('image'), 'images');
            }

            $user = User::create($credentials);
            return response()->json([
                'status' => 201,
                'info' => 'Data Created Successfully',
                'data' => $user
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'info' => 'Internal Server Error',
                'data' => $e->errorInfo
            ]);
        }
    }

    public function authLogin(Request $request)
    {
        $credentials = Validator::make($request->all(), [
            'email' => 'required|max:255',
            'password' => 'required',

        ]);

        if ($credentials->fails()) {
            return response()->json([
                'status' => '400',
                'info' => 'Validation Error',
                'data' => $credentials->errors()
            ]);
        }

        try {
            if (Auth::attempt($credentials)) {
                $user = $request->user();
                $data = [
                    'status' => 200,
                    'info' => 'Login Success',
                    'token' => $user->createToken('MyNote Token')->plainTextToken,
                    'data' => $user
                ];
                return response()->json($data, 200);
            }
        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'info' => 'Internal Server Error',
                'data' => $e->errorInfo
            ]);
        }
    }
}
