<?php

namespace App\Http\Controllers;

use Illuminate\Http\{RedirectResponse, Request};
use App\Http\Requests\SpouseUpdateRequest;
use Homeful\Contacts\Models\Customer;
use Illuminate\Support\Facades\Redirect;
use Inertia\{Inertia, Response};

class SpouseController extends Controller
{
    public function edit(Request $request): Response
    {
        return Inertia::render('Spouse/EditV2', [
            'spouse' => $request->user()->contact?->spouse
        ]);
    }

    public function update(SpouseUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $customer = Customer::find($user->contact->id);
        $customer->update(['spouse' => $request->validated()]);
        $customer->save();
        // $user->save();

        return redirect()->back();
    }
}
