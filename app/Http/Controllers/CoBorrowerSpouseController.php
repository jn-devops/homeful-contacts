<?php

namespace App\Http\Controllers;

use App\Actions\UpdateLoanProcessingContactData;
use App\Http\Requests\CoBorrowerSpouseRequest;
use App\Http\Requests\CoBorrowerUpdateRequest;
use Homeful\Contacts\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Response;
use Inertia\Inertia;

class CoBorrowerSpouseController extends Controller
{
    public function update(CoBorrowerSpouseRequest $request): RedirectResponse
    {
        $user = $request->user();

        $records = $user->contact?->co_borrowers?->toCollection()?->mapWithKeys(function ($item) {
            return [$item->type => $item];
        })->toArray() ?? [];

        $data = $request->validated();

        $type = $data['co_borrower_type'];
        unset($data['co_borrower_type']);
        $tin = Arr::pull($data, 'tin');
        Arr::set($data, 'employment.0.id.tin', $tin);
        Arr::set($data, 'employment.0.type', 'Primary');
        Arr::set($data, 'employment.0.monthly_gross_income', 10000);
        Arr::set($data, 'employment.0.employment_status', 'Regular');
        $records[$type]['spouse'] = $data;
        
        $co_borrower_records = [];
        foreach($records as $type => $co_borrower_record){
            $co_borrower_records [] = $co_borrower_record;
        }
        
        $customer = Customer::find($user->contact->id);
        $customer->update(['co_borrowers' => $co_borrower_records]);
        $customer->save();
        // $user->save();

        UpdateLoanProcessingContactData::updateContact($user->contact->id);

        return redirect()->back();
    }
}
