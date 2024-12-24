<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'translations' => __('messages'),
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $user = User::where('email', $request->email)->first();
    
        // Check if the user is inactive
        if ($user && !$user->is_active) {
            return back()->withErrors([
                'is_active' => 'Your account is inactive. Please contact support.',
            ]);
        }
    
        // Log out the previous session if it exists
        if ($user && $user->session_id) {
            \Session::getHandler()->destroy($user->session_id);
        }
    
        // Authenticate the user
        $request->authenticate();
        // Update the session ID for the current session
        // Regenerate the session to prevent session fixation
        $request->session()->regenerate();

        $user->session_id = session()->getId();
        $user->save();
    


        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $user->session_id = null;
        $user->save();
        
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
