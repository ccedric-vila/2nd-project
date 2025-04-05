<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     */
    public function showLoginForm(Request $request)
    {
        // Store intended URL for redirect after login
        if ($request->has('redirect_to')) {
            session(['url.intended' => $request->input('redirect_to')]);
        }
        
        // Store product ID if this is a "Buy Now" action
        if ($request->has('buy_now')) {
            session(['buy_now_product_id' => $request->input('buy_now')]);
        }

        return view('auth.login');
    }

    /**
     * The user has been authenticated.
     */
    protected function authenticated(Request $request, $user)
    {
        // Handle "Buy Now" redirect after login
        if (session()->has('buy_now_product_id')) {
            $productId = session()->pull('buy_now_product_id');
            return redirect()->route('checkout.single', $productId);
        }

        // Existing admin redirect logic
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Default redirect
        return redirect()->intended($this->redirectPath());
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}