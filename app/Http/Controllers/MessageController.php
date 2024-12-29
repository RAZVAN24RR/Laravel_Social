<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::with('user')->orderBy('created_at', 'desc')->get();
        return view('home', compact('messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|max:1000'
        ]);

        auth()->user()->messages()->create([
            'content' => $request->content
        ]);

        return redirect()->back();
    }
}
