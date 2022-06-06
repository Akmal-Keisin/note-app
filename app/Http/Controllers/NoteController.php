<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
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
            $notes = Note::all();
            return response()->json([
                'status' => 200,
                'info' => 'Data Obtained Successfully',
                'data' => $notes
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'info' => 'Internal Server Error',
                'data' => $e->errorInfo
            ]);
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
        $validatedData = Validator::make($request->all(), [
            'title' => 'required|max255',
            'body' => 'required'
        ]);
        if ($validatedData->fails()) {
            return response()->json([
                'status' => 401,
                'info' => 'Validation Failed',
                'data' => $validatedData->errors()
            ]);
        }
        try {
            $data = $request->all();
            Note::create($data);
            return response()->json([
                'status' => 201,
                'info' => 'Data Created Successfully',
                'data' => $data
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'info' => 'Internal Server Error',
                'data' => $e->errorInfo
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        try {
            return response()->json([
                'status' => 200,
                'info' => 'Data Obtained Successfully',
                'data' => $note
            ], 200);
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
    public function update(Request $request, Note $note)
    {
        // Validation Check

        $validatedData = Validator::make($request->all(), [
            'title' => 'nullable',
            'body' => 'nullable'
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'status' => 401,
                'info' => 'Validation Failed',
                'data' => $validatedData->errors()
            ], 401);
        }

        try {
            // Request Check
            if (!empty($validatedData)) {
                $note->update($request->all());
                return response()->json([
                    'status' => 200,
                    'info' => 'Data Created Successfully',
                    'data' => $note
                ], 201);
            }
            return response()->json([
                'status' => 200,
                'info' => 'Data Updated Successfully',
                'data' => $note
            ], 201);
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
    public function destroy(Note $note)
    {
        try {
            $note->delete();
            return response()->json([
                'status' => 200,
                'info' => 'Data Deleted Successfully',
                'data' => $note
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'info' => 'Internal Server Error',
                'data' => $e->errorInfo
            ], 500);
        }
    }
}
