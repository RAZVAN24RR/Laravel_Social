<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Logout the user and redirect to the homepage.
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'You have been logged out.');
    }
}
