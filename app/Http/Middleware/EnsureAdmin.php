<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Dashboard hanya untuk akun pengelola.
 *
 * Berbeda dengan gerbang siswa (EnsureStudentLoggedIn) yang masih berbasis
 * session, bagian ini memakai autentikasi Laravel sungguhan: kata sandinya
 * di-hash dan diperiksa ke tabel users.
 */
class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            $request->session()->put('url.intended', $request->fullUrl());

            return redirect()->route('admin.login');
        }

        if (! Auth::user()->bolehBukaDasbor()) {
            Auth::logout();

            return redirect()->route('admin.login')
                ->withErrors(['email' => 'Akun ini tidak punya akses ke dasbor.']);
        }

        return $next($request);
    }
}
