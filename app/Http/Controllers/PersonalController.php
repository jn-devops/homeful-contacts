<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonalUpdateRequest;
use Homeful\Contacts\Models\Contact;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;


class PersonalController extends Controller
{
    /**
     * Display the contact's personal information form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Personal/Edit', [
            'contact' => $request->user()->contact
        ]);
    }

    /**
     * Update the user's profile information.
     */
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

        return Redirect::route('personal.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
