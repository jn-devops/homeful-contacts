<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Validation\ValidationException;
use Homeful\References\Models\Reference;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Actions\RegisterContact;
use Inertia\{Inertia, Response};
use Spatie\Url\Url;

class RegisteredUserController extends Controller
{
    public function create(Request $request): Response
    {
        session($request->only('callback'));

        return Inertia::render('Auth/Register', [
            'callback' => $request->get('callback', config('homeful-contacts.callback')),
            'showExtra' => config('homeful-contacts.show_gmi') || $request->get('showExtra'),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): \Illuminate\Foundation\Application|\Illuminate\Routing\Redirector|\Symfony\Component\HttpFoundation\Response|RedirectResponse
    {
        $action = new RegisterContact;
        $user = $action->handle($request->all());
        $reference = $action->getReference();

        event(new Registered($user));

        Auth::login($user);

        return session('callback')
            ? Inertia::location($this->getCallback($reference))
            : redirect(route('dashboard', absolute: false));
    }

    protected function getCallback(Reference $reference): string
    {
        $url = Url::fromString(session('callback'));

        return $url->withQueryParameters(['contact_reference_code' => $reference->code]);
    }
}
