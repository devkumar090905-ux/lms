<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLibraryActive
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (\Illuminate\Support\Facades\Session::has('library_id')) {
            $library = \App\Models\LibrarySetting::find(\Illuminate\Support\Facades\Session::get('library_id'));
            
            if (!$library || !$library->is_active) {
                \Illuminate\Support\Facades\Session::flush();
                return redirect()->route('home')->withErrors(['email' => 'Your library account is suspended. Please contact the administrator.']);
            }
        }

        return $next($request);
    }
}
