<?php

namespace App\Http\Controllers;

use App\Enums\CivilStatus;
use Illuminate\Http\{RedirectResponse, Request};
use App\Http\Requests\PersonalUpdateRequest;
use Illuminate\Support\Facades\Redirect;
use Homeful\Contacts\Models\Contact;
use Inertia\{Inertia, Response};

class PersonalController extends Controller
{
    public function edit(Request $request): Response
    {
        return Inertia::render('Personal/EditV2', [
            'contact' => $request->user()->contact->getData(),
        ]);
    }

    public function update(PersonalUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        if (!$user->contact) {
            $contact = Contact::create(array_merge([
                'email' => $user->email,
                'mobile' => $user->mobile,
            ], $request->validated()));
            $user->contact()->associate($contact);
            $user->save();
        }
        else {
            $user->contact->fill($request->validated());
            $user->contact->save();
        }

        return redirect()->back();
    }
}
