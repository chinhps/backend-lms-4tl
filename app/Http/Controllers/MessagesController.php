<?php

namespace App\Http\Controllers;

use App\Models\Messages as ModelsMessages;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function GetMessage(Request $request)
    {
        $data = ModelsMessages::query();
        $message = $data->findOrFail($request->id);
        $user = $message->user11;
        return response()->json([
            // $user,
            $user
        ]);
    }
}
