<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages = $messages = Message::with('user')->latest()->get();
        return view('home', compact('messages'));
    }

    public function store(Request $request)
    {
        // 1. Validăm datele
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        // 2. Creăm mesajul folosind datele validate
        $message = Message::create([
            'content' => $validated['content'],
            'user_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Message posted successfully!');
    }
    public function destroy($id)
    {
        $message = Message::findOrFail($id);

        if(auth()->id() !== $message->user_id){
            return redirect()->back()->with('error', "You are not authorized to delete this message.");
        }

        $message->delete();

        return redirect()->back()->with('success', "Message deleted.");

    }
}
