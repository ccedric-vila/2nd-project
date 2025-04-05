<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendWelcomeEmail()
    {
        $user = (object) [
            'name' => 'John Doe',
            'email' => 'test@example.com',
        ];
        
        Mail::to($user->email)->send(new WelcomeEmail($user, '123456'));
        
        return back()->with('success', 'Email sent!');
    }
}