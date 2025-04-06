<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Show the email verification notice.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('auth.verify');
    }

    /**
     * Verify the user's email address.
     *
     * @param  string  $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify($token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return redirect('/register')->with('error', 'Invalid verification token.');
        }

        $user->update([
            'email_verified_at' => now(),
            'verification_token' => null,
        ]);

        return redirect('/home')->with('success', 'Email verified successfully!');
    }
}