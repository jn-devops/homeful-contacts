<?php

namespace App\Http\Controllers;

use App\Http\Requests\CoBorrowerAddressRequest;
use App\Http\Requests\CoBorrowerUpdateRequest;
use Illuminate\Support\Facades\Redirect;
use Homeful\Contacts\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Response;
use Inertia\Inertia;

class CoBorrowerAddressController extends Controller
{
    public function update(CoBorrowerAddressRequest $request): RedirectResponse
    {
        $user = $request->user();

        $records = $user->contact?->co_borrowers?->toCollection()?->mapWithKeys(function ($item) {
            return [$item->type->value => $item];
        })->toArray() ?? [];

        $data = $request->validated();

        $type = $data['co_borrower_type'];
        unset($data['co_borrower_type']);
        $records[$type]['addresses'] = [$data];
        
        $co_borrower_records = [];
        foreach($records as $type => $co_borrower_record){
            $co_borrower_records [] = $co_borrower_record;
        }
        
        $customer = Customer::find($user->contact->id);
        $customer->update(['co_borrowers' => $co_borrower_records]);
        $customer->save();
        // $user->save();

        return redirect()->back();
    }
}
