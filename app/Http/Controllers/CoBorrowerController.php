<?php

namespace App\Http\Controllers;

use App\Actions\UpdateLoanProcessingContactData;
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
        $address = $records[$type]['addresses'] ?? [];
        $spouse = $records[$type]['spouse'] ?? [];
        $employment = $records[$type]['employment'] ?? [];
        $records[$type] = $data;
        if(!empty($address)){
            $records[$type]['addresses'][0]['type'] = $address[0]['type'];
            $records[$type]['addresses'][0]['ownership'] = $address[0]['ownership'];
            $records[$type]['addresses'][0]['address1'] = $address[0]['address1'];
            $records[$type]['addresses'][0]['locality'] = $address[0]['locality'];
            $records[$type]['addresses'][0]['sublocality'] = $address[0]['sublocality'];
            $records[$type]['addresses'][0]['administrative_area'] = $address[0]['administrative_area'];
            $records[$type]['addresses'][0]['postal_code'] = $address[0]['postal_code'];
            $records[$type]['addresses'][0]['region'] = $address[0]['region'];
            $records[$type]['addresses'][0]['country'] = $address[0]['country'];
        }
        if(!empty($spouse)){
            $records[$type]['spouse']['first_name'] = $spouse['first_name'];
            $records[$type]['spouse']['middle_name'] = $spouse['middle_name'];
            $records[$type]['spouse']['last_name'] = $spouse['last_name'];
            $records[$type]['spouse']['name_suffix'] = $spouse['name_suffix'];
            $records[$type]['spouse']['mothers_maiden_name'] = $spouse['mothers_maiden_name'];
            $records[$type]['spouse']['civil_status'] = $spouse['civil_status'];
            $records[$type]['spouse']['sex'] = $spouse['sex'];
            $records[$type]['spouse']['nationality'] = $spouse['nationality'];
            $records[$type]['spouse']['date_of_birth'] = $spouse['date_of_birth'];
            $records[$type]['spouse']['email'] = $spouse['email'];
            $records[$type]['spouse']['mobile'] = $spouse['mobile'];
            $records[$type]['spouse']['other_mobile'] = $spouse['other_mobile'];
            $records[$type]['spouse']['landline'] = $spouse['landline'];
            $records[$type]['spouse']['employment'][0]['id']['tin'] = $spouse['employment'][0]['id']['tin'] ?? '---';
            $records[$type]['spouse']['employment'][0]['type'] = $spouse['employment'][0]['type'] ?? 'Primary';
            $records[$type]['spouse']['employment'][0]['monthly_gross_income'] = $spouse['employment'][0]['monthly_gross_income'] ?? 10000;
            $records[$type]['spouse']['employment'][0]['employment_status'] = $spouse['employment'][0]['employment_status'] ?? 'Regular';
        }
        if(!empty($employment)){
            $records[$type]['employment'][0]['type'] = $employment[0]['type'] ?? null;
            $records[$type]['employment'][0]['employment_status'] = $employment[0]['employment_status'] ?? null;
            $records[$type]['employment'][0]['monthly_gross_income'] = $employment[0]['monthly_gross_income'] ?? null;
            $records[$type]['employment'][0]['current_position'] = $employment[0]['current_position'] ?? null;
            $records[$type]['employment'][0]['employment_type'] = $employment[0]['employment_type'] ?? null;
            $records[$type]['employment'][0]['employer']['name'] = $employment[0]['employer']['name'] ?? null;
            $records[$type]['employment'][0]['employer']['email'] = $employment[0]['employer']['email'] ?? null;
            $records[$type]['employment'][0]['employer']['contact_no'] = $employment[0]['employer']['contact_no'] ?? null;
            $records[$type]['employment'][0]['employer']['nationality'] = $employment[0]['employer']['nationality'] ?? null;
            $records[$type]['employment'][0]['employer']['industry'] = $employment[0]['employer']['industry'] ?? null;
            $records[$type]['employment'][0]['employer']['address']['type'] = $employment[0]['employer']['address']['type'] ?? null;
            $records[$type]['employment'][0]['employer']['address']['ownership'] = $employment[0]['employer']['address']['ownership'] ?? null;
            $records[$type]['employment'][0]['employer']['address']['address1'] = $employment[0]['employer']['address']['address1'] ?? null;
            $records[$type]['employment'][0]['employer']['address']['locality'] = $employment[0]['employer']['address']['locality'] ?? null;
            $records[$type]['employment'][0]['employer']['address']['administrative_area'] = $employment[0]['employer']['address']['administrative_area'] ?? null;
            $records[$type]['employment'][0]['employer']['address']['postal_code'] = $employment[0]['employer']['address']['postal_code'] ?? null;
            $records[$type]['employment'][0]['employer']['address']['region'] = $employment[0]['employer']['address']['region'] ?? null;
            $records[$type]['employment'][0]['employer']['address']['country'] = $employment[0]['employer']['address']['country'] ?? null;
            $records[$type]['employment'][0]['id']['tin'] = $employment[0]['id']['tin'] ?? null;
            $records[$type]['employment'][0]['id']['pagibig'] = $employment[0]['id']['pagibig'] ?? null;
            $records[$type]['employment'][0]['id']['sss'] = $employment[0]['id']['sss'] ?? null;
            $records[$type]['employment'][0]['id']['gsis'] = $employment[0]['id']['gsis'] ?? null;
        }

        $co_borrower_records = [];
        foreach($records as $type => $address_record){
            $co_borrower_records [] = $address_record;
        }

        $customer = Customer::find($user->contact->id);
        $customer->update(['co_borrowers' => $co_borrower_records]);
        $customer->save();
        // $user->save();

        UpdateLoanProcessingContactData::updateContact($user->contact->id);

        return redirect()->back();
    }
}
