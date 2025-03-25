<?php

namespace App\Http\Controllers;

use App\Actions\UpdateLoanProcessingContactData;
use App\Http\Requests\AddressUpdateRequest;
use Homeful\Contacts\Models\Customer;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;

class AddressController extends Controller
{
    public function edit(Request $request): Response
    {
        return Inertia::render('Address/EditV2', [
            'contact' => $request->user()->contact,
            'lazarus_url' => config('homeful-contacts.lazarus_url'),
            'lazarus_token' => config('homeful-contacts.lazarus_api_token'),
        ]);
    }

    public function update(AddressUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $records = $user->contact?->addresses?->toCollection()?->mapWithKeys(function ($item) {
            return [$item->type->value => $item];
        })->toArray() ?? [];

        $data = $request->validated();
        $type = $data['type'];
        $records[$type] = $data;

        $address_records = [];
        foreach($records as $type => $address_record){
            $address_records [] = $address_record;
        }

        $customer = Customer::find($user->contact->id);
        $customer->update(['addresses' => $address_records]);
        $customer->save();
        // $user->save();

        UpdateLoanProcessingContactData::updateContact($user->contact->id);

        return redirect()->back();
    }
}
