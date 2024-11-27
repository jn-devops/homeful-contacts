<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressUpdateRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;


class AddressController extends Controller
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
    public function update(AddressUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $records = [];
        foreach ((array) $user->contact->addresses as $address){
            $type = $address['type'];
            $records[$type] = $address;
        }

        $data = $request->validated();
        $type = $data['type'];
        $records[$type] = $data;

        $addresses = [];
        foreach($records as $type => $address){
            $addresses [] = $address;
        }

        $user->contact->update(['addresses' => $addresses]);
        $user->contact->save();
        $user->save();

        return Redirect::route('personal.edit');
    }
}
