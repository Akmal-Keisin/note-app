<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $user = User::find(Auth::user()->id);

            // checking for the request
            if (request('search')) {
                $notes = $user->note()->where('title', 'like', '%' . request('search') . '%')->get();
            } else if (request('paginate')) {
                $notes = $user->note()->paginate(request('paginate'));
            } else {
                $notes = $user->note()->get();
            }

            // return success
            return response()->json([
                'status' => 200,
                'info' => 'Data Obtained Successfully',
                'data' => $notes
            ], 200);

            // catch query exception
        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'info' => 'Internal Server Error',
                'data' => $e->errorInfo
            ], 500);
        }
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
        $validatedData = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'body' => 'required',
            'image' => 'nullable|image'
        ]);

        // return error when validation fail
        if ($validatedData->fails()) {
            return response()->json([
                'status' => 401,
                'info' => 'Validation Failed',
                'data' => $validatedData->errors()
            ], 401);
        }
        try {
            // get all data from request
            $data = $request->all();

            // storing image
            if ($request->image !== null) {
                $data['image'] = env('APP_BASE_URL') . '/storage/' . $request->file('image')->store('images');
            } else {
                $data['image'] = $request->image;
            }

            // get user_id from user login
            // $data['user_id'] = Auth::user()->id;
            $data['user_id'] = Auth::user()->id;

            // create data
            Note::create($data);

            // return success
            return response()->json([
                'status' => 201,
                'info' => 'Data Created Successfully',
                'data' => $data
            ], 201);

            // catch query exception error
        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'info' => 'Internal Server Error',
                'data' => $e->errorInfo
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = User::find(Auth::user()->id);
            $usernote = $user->note()->where('id', $id)->first();
            if ($usernote) {
                return response()->json([
                    'status' => 200,
                    'info' => 'Data Obtained Successfully',
                    'data' => $usernote
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'info' => 'Data Not Found',
                    'data' => $usernote
                ], 404);
            }
        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'info' => 'Internal Server Error',
                'data' => $e
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // validating data
        $validatedData = Validator::make($request->all(), [
            'title' => 'nullable|max:255',
            'body' => 'nullable',
            'image' => 'nullable|image'
        ]);

        // return back if validation fail
        if ($validatedData->fails()) {
            return response()->json([
                'status' => 401,
                'info' => 'Validation Failed',
                'data' => $validatedData->errors()
            ], 401);
        }

        try {
            // find data
            $user = User::find(Auth::user()->id);
            $usernote = $user->note()->where('id', $id)->first();

            // check is note exist or not
            if ($usernote) {

                // check is requst empty or not
                if (!empty($validatedData)) {

                    // get all data from request
                    $data = $request->all();

                    // storing image
                    if ($request->image !== null) {
                        Storage::delete($usernote->image);
                        $data['image'] = env('APP_BASE_URL') . '/storage/' . $request->file('image')->store('images');
                    } else {
                        $data['image'] = $usernote->image;
                    }

                    // updating data
                    $usernote->update($request->all());

                    // return success
                    return response()->json([
                        'status' => 200,
                        'info' => 'Data Updated Successfully',
                        'data' => $usernote
                    ], 201);
                }
                return response()->json([
                    'status' => 200,
                    'info' => 'Data Updated Successfully',
                    'data' => $usernote
                ], 201);

                // return 404 when data not found
            } else {
                return response()->json([
                    'status' => 404,
                    'info' => 'Data Not Found'
                ], 404);
            }
            // catch query exception
        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'info' => 'Internal Server Error',
                'data' => $e->errorInfo
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // find data by id
            $user = User::find(Auth::user()->id);
            $note = $user->note()->where('id', $id)->first();

            // check is data exist or not
            if ($note) {

                // deleting image
                if ($note->image !== env('APP_BASE_URL') . 'images/notedefault.png') {
                    Storage::delete($note->image);
                }

                // deleting data
                $note->delete();

                // return success
                return response()->json([
                    'status' => 200,
                    'info' => 'Data Deleted Successfully'
                ], 200);

                // return error 404 when data not found
            } else {
                return response()->json([
                    'status' => 404,
                    'info' => 'Data Not Found'
                ], 404);
            }

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
