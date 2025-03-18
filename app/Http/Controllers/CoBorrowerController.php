<?php

namespace App\Http\Controllers;

use App\Http\Requests\CoBorrowerUpdateRequest;
use Homeful\Contacts\Models\Customer;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;

class CoBorrowerController extends Controller
{
    public function edit(Request $request): Response
    {
        // dd($request->user()->contact);
        return Inertia::render('CoBorrower/EditV2', [
            'contact' => $request->user()->contact,
            'lazarus_url' => config('homeful-contacts.lazarus_url'),
            'lazarus_token' => config('homeful-contacts.lazarus_api_token'),
        ]);
    }

    public function update(CoBorrowerUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $records = $user->contact?->co_borrowers?->toCollection()?->mapWithKeys(function ($item) {
            return [$item->type->value => $item];
        })->toArray() ?? [];

        $data = $request->validated();
        $type = $data['type'];
        $records[$type] = $data;

        $co_borrower_records = [];
        foreach($records as $type => $address_record){
            $co_borrower_records [] = $address_record;
        }

        $customer = Customer::find($user->contact->id);
        $customer->update(['co_borrowers' => $co_borrower_records]);
        $customer->save();
        // $user->save();

        return redirect()->back();
    }
}
