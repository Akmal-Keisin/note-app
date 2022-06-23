<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PinController extends Controller
{
    public function pin(Request $request)
    {
        try {
            $note = Note::find($request->id);
            if ($note && $note->user_id == Auth::user()->id) {
                if ($note->pin == true) {
                    $note->pin = false;
                    $note->save();

                    return response()->json([
                        'status' => 200,
                        'message' => "Pin Success",
                        'data' => $note
                    ], 200);
                } else {
                    $note->pin = true;
                    $note->save();

                    return response()->json([
                        'status' => 200,
                        'message' => "Pin Success",
                        'data' => $note
                    ], 200);
                }
            }
            return response()->json([
                'status' => 404,
                'message' => "Note Not Found"
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server Error',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function getAllPinnedNotes()
    {
        try {
            $notes = Note::where('user_id', Auth::user()->id)->where('pin', true)->get();
            if (count($notes) > 0) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Notes Obtained Successfully',
                    'data' => $notes
                ], 200);
            }
            return response()->json([
                'status' => 404,
                'message' => 'Pinned Notes Empty'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server Error',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}
