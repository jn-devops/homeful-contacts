<?php

namespace App\Http\Controllers;

use App\Actions\UpdateLoanProcessingContactData;
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
            'spouse' => $request->user()->contact?->spouse,
            'lazarus_url' => config('homeful-contacts.lazarus_url'),
            'lazarus_token' => config('homeful-contacts.lazarus_api_token'),
        ]);
    }

    public function update(SpouseUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $customer = Customer::find($user->contact->id);
        $customer->update(['spouse' => $request->validated()]);
        $customer->save();
        // $user->save();

        UpdateLoanProcessingContactData::updateContact($user->contact->id);

        return redirect()->back();
    }
}
