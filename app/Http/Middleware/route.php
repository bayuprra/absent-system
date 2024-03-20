<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class route
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->session()->get('data')->nama_role ?? "";
        dump($user);
        if (strtolower($user) == 'admin') {
            return redirect('/dashboard');
        } elseif (strtolower($user) == 'karyawan') {
            return redirect('/userAbsent');
        }
        return $next($request);
    }
}
