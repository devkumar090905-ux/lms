<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LibrarySetting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class LandingController extends Controller
{
    public function index()
    {
        // If already logged in, redirect to dashboard
        if (Session::has('library_id')) {
            return redirect()->route('admin.dashboard');
        }

        return view('welcome');
    }

    public function setup(Request $request)
    {
        $request->validate([
            'owner_name' => 'required|string|max:255',
            'library_name' => 'required|string|max:255',
            'address' => 'required|string',
            'total_seats' => 'required|integer|min:1',
            'opening_time' => 'required|string',
            'closing_time' => 'required|string',
            'email' => 'required|email|unique:library_settings,email',
            'mobile_number' => 'required|string|max:15',
            'password' => 'required|min:6',
        ]);

        $library = LibrarySetting::create([
            'owner_name' => $request->owner_name,
            'library_name' => $request->library_name,
            'address' => $request->address,
            'total_seats' => $request->total_seats,
            'opening_time' => $request->opening_time,
            'closing_time' => $request->closing_time,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'password' => Crypt::encryptString($request->password),
        ]);

        Session::put('library_id', $library->id);
        Session::put('library_name', $library->library_name);
        Session::put('owner_name', $library->owner_name);

        return redirect()->route('admin.dashboard')->with('success', 'Library Setup Successful!');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $library = LibrarySetting::where('email', $request->email)->first();

        if ($library) {
            if (!$library->is_active) {
                return back()->withErrors(['email' => 'Your library account is suspended. Please contact the administrator.']);
            }

            try {
                if (Crypt::decryptString($library->password) === $request->password) {
                    Session::put('library_id', $library->id);
                    Session::put('library_name', $library->library_name);
                    Session::put('owner_name', $library->owner_name);
                    return redirect()->route('admin.dashboard')->with('success', 'Welcome Back!');
                }
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                // Fallback for old hashed passwords
                if (Hash::check($request->password, $library->password)) {
                    // Update to encrypted password
                    $library->update(['password' => Crypt::encryptString($request->password)]);
                    
                    Session::put('library_id', $library->id);
                    Session::put('library_name', $library->library_name);
                    Session::put('owner_name', $library->owner_name);
                    return redirect()->route('admin.dashboard')->with('success', 'Welcome Back!');
                }
            }
        }

        return back()->withErrors(['email' => 'Invalid Credentials']);
    }

    public function logout()
    {
        Session::forget(['library_id', 'library_name']);
        return redirect()->route('home');
    }
}
