<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmploymentUpdateRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Response;
use Inertia\Inertia;

class SpouseEmploymentController extends Controller
{
    public function edit(Request $request): Response
    {
        return Inertia::render('Spouse/Edit', [
            'spouse' => $request->user()->contact?->spouse
        ]);
    }

    public function update(EmploymentUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validated();
        // transform employer data
        $employer_name = Arr::pull($data, 'employer_name');
        if ($employer_name) {
            Arr::set($data, 'employer.name', $employer_name);
            $employer_email = Arr::pull($data, 'employer_email');
            Arr::set($data, 'employer.email', $employer_email);
            $employer_contact_no = Arr::pull($data, 'employer_contact_no');
            Arr::set($data, 'employer.contact_no', $employer_contact_no);
            $employer_nationality = Arr::pull($data, 'employer_nationality');
            Arr::set($data, 'employer.nationality', $employer_nationality);
            $employer_industry = Arr::pull($data, 'employer_industry');
            Arr::set($data, 'employer.industry', $employer_industry);

            $employer_address_type = Arr::pull($data, 'employer_address_type');
            Arr::set($data, 'employer.address.type', $employer_address_type);
            $employer_address_ownership = Arr::pull($data, 'employer_address_ownership');
            Arr::set($data, 'employer.address.ownership', $employer_address_ownership);
            $employer_address_address1 = Arr::pull($data, 'employer_address_address1');
            Arr::set($data, 'employer.address.address1', $employer_address_address1);
            $employer_address_locality = Arr::pull($data, 'employer_address_locality');
            Arr::set($data, 'employer.address.locality', $employer_address_locality);
            $employer_address_administrative_area = Arr::pull($data, 'employer_address_administrative_area');
            Arr::set($data, 'employer.address.administrative_area', $employer_address_administrative_area);
            $employer_address_postal_code = Arr::pull($data, 'employer_address_postal_code');
            Arr::set($data, 'employer.address.postal_code', $employer_address_postal_code);
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
        $records[$type] = $data;

        $employment_records = [];
        foreach($records as $type => $employment_record){
            $employment_records [] = $employment_record;
        }

        $spouse = $user->contact->spouse->toArray();
        $spouse['employment'] = $employment_records;

        $user->contact->update(['spouse' => $spouse]);
        $user->contact->save();
        $user->save();

        return Redirect::route('spouse.edit');
    }
}
