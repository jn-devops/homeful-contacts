<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\{Auth, Hash};
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules;
use Inertia\{Inertia, Response};
use Illuminate\Support\Arr;
use App\Models\User;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): Response
    {
        session($request->only('callback'));

        return Inertia::render('Auth/Register', [
            'callback' => $request->get('callback')
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^(\w+) (.*)$/'],
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'mobile' => 'required|string|max:11',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return session('callback')
            ? redirect()->back()
            : redirect(route('dashboard', absolute: false));
    }
}
