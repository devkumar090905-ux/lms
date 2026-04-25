<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LibrarySetting;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        // Simple role check (In a real app, use Middleware)
        if (!Session::has('is_super_admin')) {
            return redirect()->route('superadmin.login.view');
        }

        $libraries = LibrarySetting::all();
        
        // Calculate usage duration for each
        foreach ($libraries as $lib) {
            $lib->usage_duration = Carbon::parse($lib->created_at)->diffForHumans();
        }

        return view('superadmin.dashboard', compact('libraries'));
    }

    public function loginView()
    {
        return view('superadmin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->where('role', 'super_admin')->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Session::put('super_admin_id', $user->id);
            Session::put('is_super_admin', true);
            return redirect()->route('superadmin.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid Super Admin credentials']);
    }

    public function editLibrary($id)
    {
        $library = LibrarySetting::findOrFail($id);
        return view('superadmin.edit_library', compact('library'));
    }

    public function updateLibrary(Request $request, $id)
    {
        $library = LibrarySetting::findOrFail($id);
        
        $request->validate([
            'owner_name' => 'required',
            'library_name' => 'required',
            'total_seats' => 'required|integer',
        ]);

        $library->update($request->all());

        if ($request->filled('password')) {
            $library->update(['password' => Crypt::encryptString($request->password)]);
        }

        return redirect()->route('superadmin.dashboard')->with('success', 'Library updated successfully');
    }

    public function deleteLibrary($id)
    {
        LibrarySetting::destroy($id);
        return back()->with('success', 'Library deleted successfully');
    }

    public function logout()
    {
        Session::forget(['super_admin_id', 'is_super_admin']);
        return redirect()->route('superadmin.login.view');
    }
}
