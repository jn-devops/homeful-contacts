<?php

namespace App\Http\Controllers;

use App\Actions\UpdateLoanProcessingContactData;
use App\Http\Requests\CoBorrowerEmploymentUpdateRequest;
use Homeful\Contacts\Models\Customer;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Response;
use Inertia\Inertia;

class CoBorrowerEmploymentController extends Controller
{
    public function edit(Request $request): Response
    {
        return Inertia::render('CoBorrower/Edit', [
            'co_borrowers' => $request->user()->contact?->co_borrowers
        ]);
    }

    public function update(CoBorrowerEmploymentUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $records = $user->contact?->co_borrowers?->toCollection()?->mapWithKeys(function ($item) {
            return [$item->type => $item];
        })->toArray() ?? [];

        $data = $request->validated();
        // transform employer data
        $employer_name = Arr::pull($data, 'employer_name');
        if ($employer_name) {
            Arr::set($data, 'employer.name', $employer_name);

            $employment = Arr::pull($data, 'co_borrower_employment');
            Arr::set($data, 'type', $employment);
            $employment_rank = Arr::pull($data, 'employment_rank');
            Arr::set($data, 'rank', $employment_rank);
            $employment_years_in_service = Arr::pull($data, 'employment_years_in_service');
            Arr::set($data, 'years_in_service', $employment_years_in_service);

            $employer_email = Arr::pull($data, 'employer_email');
            Arr::set($data, 'employer.email', $employer_email);
            $employer_contact_no = Arr::pull($data, 'employer_contact_no');
            Arr::set($data, 'employer.contact_no', $employer_contact_no);
            $employer_nationality = Arr::pull($data, 'employer_nationality');
            Arr::set($data, 'employer.nationality', $employer_nationality);
            $employer_industry = Arr::pull($data, 'employer_industry');
            Arr::set($data, 'employer.industry', $employer_industry);
            $employer_total_number_of_employees = Arr::pull($data, 'employer_total_number_of_employees');
            Arr::set($data, 'employer.total_number_of_employees', $employer_total_number_of_employees);
            $employer_year_established = Arr::pull($data, 'employer_year_established');
            Arr::set($data, 'employer.year_established', $employer_year_established);

            $employer_address_type = Arr::pull($data, 'employer_address_type');
            Arr::set($data, 'employer.address.type', $employer_address_type);
            $employer_address_ownership = Arr::pull($data, 'employer_address_ownership');
            Arr::set($data, 'employer.address.ownership', $employer_address_ownership);
            $employer_address_address1 = Arr::pull($data, 'employer_address_address1');
            Arr::set($data, 'employer.address.address1', $employer_address_address1);
            $employer_address_sublocality = Arr::pull($data, 'employer_address_sublocality');
            Arr::set($data, 'employer.address.sublocality', $employer_address_sublocality);
            $employer_address_locality = Arr::pull($data, 'employer_address_locality');
            Arr::set($data, 'employer.address.locality', $employer_address_locality);
            $employer_address_administrative_area = Arr::pull($data, 'employer_address_administrative_area');
            Arr::set($data, 'employer.address.administrative_area', $employer_address_administrative_area);
            // $employer_address_postal_code = Arr::pull($data, 'employer_address_postal_code');
            // Arr::set($data, 'employer.address.postal_code', $employer_address_postal_code);
            $employer_address_region = Arr::pull($data, 'employer_address_region');
            Arr::set($data, 'employer.address.region', $employer_address_region);
            $employer_address_country = Arr::pull($data, 'employer_address_country');
            Arr::set($data, 'employer.address.country', $employer_address_country);

            $tin = Arr::pull($data, 'tin');
            Arr::set($data, 'id.tin', $tin);
            $pagibig = Arr::pull($data, 'pagibig');
            Arr::set($data, 'id.pagibig', $pagibig);
            $sss = Arr::pull($data, 'sss');
            Arr::set($data, 'id.sss', $sss);
            $gsis = Arr::pull($data, 'gsis');
            Arr::set($data, 'id.gsis', $gsis);
        }

        $type = $data['type'];
        $records[$type]['employment'] = [$data];
        
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
