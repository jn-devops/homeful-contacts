<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressUpdateRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;
use Spatie\LaravelData\DataCollection;

class AddressController extends Controller
{
    public function edit(Request $request): Response
    {
        return Inertia::render('Personal/Edit', [
            'contact' => $request->user()->contact
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

        $user->contact->update(['addresses' => $address_records]);
        $user->contact->save();
        $user->save();

        return Redirect::route('personal.edit');
    }

//    public function update(AddressUpdateRequest $request): RedirectResponse
//    {
//        $user = $request->user();
//
//        $records = [];
//        foreach ((array) $user->contact->addresses as $address){
//            $type = $address['type'];
//            $records[$type] = $address;
//        }
//
//        $data = $request->validated();
//        $type = $data['type'];
//        $records[$type] = $data;
//
//        $address_records = [];
//        foreach($records as $type => $address_record){
//            $address_records [] = $address_record;
//        }
//
//        $user->contact->update(['addresses' => $address_records]);
//        $user->contact->save();
//        $user->save();
//
//        return Redirect::route('personal.edit');
//    }
}
