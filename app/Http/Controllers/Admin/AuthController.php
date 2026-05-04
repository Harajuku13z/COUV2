<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (session('admin_authenticated')) {
            return redirect('/admin');
        }

        return view('admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $storedEmail    = Setting::query()->where('key', 'admin_email')->value('value');
        $storedPassword = Setting::query()->where('key', 'admin_password')->value('value');

        if (
            $storedEmail
            && $storedPassword
            && $request->email === $storedEmail
            && Hash::check($request->password, $storedPassword)
        ) {
            $request->session()->put('admin_authenticated', true);
            $request->session()->regenerate();

            return redirect()->intended('/admin');
        }

        return back()->withErrors(['email' => 'Identifiants incorrects.'])->withInput();
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('admin_authenticated');
        $request->session()->regenerate();

        return redirect('/admin/login');
    }
}
