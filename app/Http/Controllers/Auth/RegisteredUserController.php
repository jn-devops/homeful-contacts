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
use Illuminate\Support\Str;
use Spatie\Url\Url;

class RegisteredUserController extends Controller
{
    const REGISTER_VIEW = 'Auth/Register';

    public function create(Request $request): Response
    {
        $this->setCallbackToSession($request);

        $callback = $request->get('callback', config('homeful-contacts.callback'));
        $showExtra = config('homeful-contacts.show_gmi') || $request->get('showExtra');
        $hidePassword = config('homeful-contacts.hide_password') || $request->get('hidePassword');
        $autoPassword = $hidePassword ? config('homeful-contacts.default_password') : '';

        return Inertia::render(self::REGISTER_VIEW, [
            'callback' => $callback,
            'showExtra' => $showExtra,
            'autoPassword' => $autoPassword,
            'name' => $request->get('name',''),
            'email' => $request->get('email',''),
            'mobile' => $request->get('mobile',''),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): \Illuminate\Foundation\Application|\Illuminate\Routing\Redirector|\Symfony\Component\HttpFoundation\Response|RedirectResponse
    {
        $user = app(RegisterContact::class)->run($request->all());

        event(new Registered($user));

        Auth::login($user);

        return session('callback')
            ? Inertia::location($this->getCallbackWithQueryParamsFromSession(context('reference')))
            : redirect(route('dashboard', absolute: false));
    }

    protected function setCallbackToSession(Request $request): self
    {
        session($request->only('callback'));

        return $this;
    }

    protected function getCallbackWithQueryParamsFromSession(Reference $reference): string
    {
        $url = Url::fromString(session('callback'));

        return $url->withQueryParameters(['contact_reference_code' => $reference->code]);
    }
}
