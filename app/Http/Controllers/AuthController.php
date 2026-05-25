<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryPartner;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /*
    |----------------------------------------
    | SHOW LOGIN
    |----------------------------------------
    */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    /*
    |----------------------------------------
    | SHOW REGISTER
    |----------------------------------------
    */
    public function showRegister()
    {
        return view('auth.register');
    }

    /*
    |----------------------------------------
    | REGISTER
    |----------------------------------------
    */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'status' => 'active',
            'registration_time' => now(),
        ]);

        Auth::login($user);

        return redirect()->route('home');
    }

    /*
    |----------------------------------------
    | LOGIN (ALL MODULES)
    |----------------------------------------
    */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $partner = DeliveryPartner::where('email', $request->email)->first();

        if ($partner && $this->passwordMatches($request->password, $partner->password)) {
            Auth::guard('delivery_partner')->login($partner, true);

            return redirect()->route('delivery-partner.dashboard');
        }

        $user = User::where('email', $request->email)->first();

        if ($user && $this->passwordMatches($request->password, $user->password)) {
            if ($user->status === 'blocked') {
                return back()->with('error', 'Your account is blocked')->withInput();
            }

            Auth::login($user);

            if ($user->role === 'admin') {
                Auth::guard('admin')->login($user, true);
            }

            $user->update([
                'login_time' => now(),
            ]);

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('home');
        }

        return back()->with('error', 'Invalid credentials')->withInput();
    }

    private function passwordMatches(string $plainPassword, ?string $storedPassword): bool
    {
        if (! is_string($storedPassword) || $storedPassword === '') {
            return false;
        }

        try {
            if (Hash::check($plainPassword, $storedPassword)) {
                return true;
            }
        } catch (\Exception $e) {
            // Plain text fallback below keeps older seeded accounts working.
        }

        return trim($plainPassword) === trim($storedPassword);
    }
    /*
    |----------------------------------------
    | LOGOUT
    |----------------------------------------
    */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

    /*
    |----------------------------------------
    | GOOGLE LOGIN
    |----------------------------------------
    */
    public function redirectToGoogle()
    {
        $googleConfig = config('services.google');
        if (empty($googleConfig['client_id']) || empty($googleConfig['client_secret']) || empty($googleConfig['redirect'])) {
            return redirect()->route('login')
                ->with('error', 'Google login is not configured. Please use email/password login instead.');
        }

        return Socialite::driver('google')->redirect();
    }

    /*
    |----------------------------------------
    | GOOGLE CALLBACK
    |----------------------------------------
    */
    public function handleGoogleCallback()
    {
        try {

            $googleUser = Socialite::driver('google')
                ->stateless()
                ->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {

                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make(uniqid()),
                    'role' => 'customer',
                    'status' => 'active',
                    'registration_time' => now(),
                    'login_time' => now(),
                ]);

            } else {

                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'login_time' => now(),
                ]);
            }

            Auth::login($user);

            return redirect()->route('home');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
