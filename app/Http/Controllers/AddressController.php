<?php

namespace App\Http\Controllers;

use App\Actions\UpdateLoanProcessingContactData;
use App\Http\Requests\AddressUpdateRequest;
use Homeful\Contacts\Classes\AddressMetadata;
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
            'duplicate_data' => $this->checkSameAddress($request),
        ]);
    }

    public function update(AddressUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $records = $user->contact?->addresses?->toCollection()?->mapWithKeys(function ($item) {
            return [$item->type => $item];
        })->toArray() ?? [];

        $data = $request->validated();
        $sameWithPermanentAddress = $data['sameWithPermanentAddress'] ?? false;
        unset($data['sameWithPermanentAddress']);
        if($sameWithPermanentAddress){
            $present = $data;
            $present['type'] = 'Present';
            $permanent = $data;
            $permanent['type'] = 'Permanent';
    
            $address_records[] = $present;
            $address_records[] = $permanent;
        }else{
            $type = $data['type'];
            $records[$type] = $data;
    
            $address_records = [];
            foreach($records as $type => $address_record){
                $address_records [] = $address_record;
            }
        }
        

        $customer = Customer::find($user->contact->id);
        $customer->update(['addresses' => $address_records]);
        $customer->save();
        // $user->save();

        UpdateLoanProcessingContactData::updateContact($user->contact->id);

        return redirect()->back();
    }

    private function checkSameAddress(Request $request): bool{
        if($request->user()->contact->addresses && isset($request->user()->contact->addresses[0])){
            if(isset($request->user()->contact->addresses[1]) && $request->user()->contact->addresses[0] instanceof AddressMetadata && $request->user()->contact->addresses[1] instanceof AddressMetadata){
                $array1 = $request->user()->contact->addresses[0]->toArray();
                unset($array1['type']);
                $array2 = $request->user()->contact->addresses[1]->toArray();
                unset($array2['type']);
                if (array_diff($array1, $array2) === [] && array_diff($array2, $array1) === []) {
                   return true;
                } else {
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
