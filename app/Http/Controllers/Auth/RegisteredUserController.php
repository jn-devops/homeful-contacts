<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Actions\RegisterContact;
use Inertia\{Inertia, Response};

class RegisteredUserController extends Controller
{
    public function create(Request $request): Response
    {
        session($request->only('callback'));

        return Inertia::render('Auth/Register', [
            'callback' => $request->get('callback')
        ]);
    }

    public function store(Request $request): \Illuminate\Foundation\Application|\Illuminate\Routing\Redirector|RedirectResponse
    {
        $user = app(RegisterContact::class)->run($request->all());

        Auth::login($user);

        return session('callback')
            ? redirect()->back()
            : redirect(route('dashboard', absolute: false));
    }
}
