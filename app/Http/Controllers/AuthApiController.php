<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function PHPSTORM_META\map;

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
            ], 400);
        }
        $data = $request->all();
        try {
            if ($request->hasFile('image')) {
                $data['image'] = 'https://magang.crocodic.net/ki/kelompok_3/note-backend/public/' . $request->file('image')->store('images');
            } else {
                $data['image'] = 'https://magang.crocodic.net/ki/kelompok_3/note-backend/public/images/default.png';
            }
            $data['password'] = Hash::make($request->password);
            $user = User::create($data);
            return response()->json([
                'status' => 201,
                'info' => 'Data Created Successfully',
                'data' => $user
            ], 201);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'info' => 'Internal Server Error',
                'data' => $e->errorInfo
            ], 500);
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
            ], 400);
        }

        try {
            // get all request data
            $data = $request->all();

            // checking for user
            if (Auth::attempt($data)) {
                $user = $request->user();
                $data = [
                    'status' => 200,
                    'info' => 'Login Success',
                    'token' => $user->createToken('MyNote Token')->plainTextToken,
                    'data' => $user
                ];
                return response()->json($data, 200);
            }

            // return login failed if user not exist / wrong password
            return response()->json([
                'status' => 400,
                'info' => 'Login Gagal',
                'data' => 'Email Atau Password Tidak Valid'
            ], 400);

            // catch query exception
        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'info' => 'Internal Server Error',
                'data' => $e->errorInfo
            ], 500);
        }
    }

    public function authLogout(Request $request)
    {
        try {
            // checking is user send bearer token or not
            if ($request->bearerToken()) {
                // deleting user token
                $request->user()->currentAccessToken()->delete();
                return response()->json([
                    'status' => 200,
                    'info' => 'Logout Success'
                ], 200);
            }

            // return logout failed if bearer token isn't set yet
            return response()->json([
                'status' => 401,
                'info' => 'Logout Failed',
                'data' => 'Unauthenticated'
            ], 401);

            // catch query exception
        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'info' => 'Internal Server Error',
                'data' => $e->errorInfo
            ], 500);
        }
    }
}
