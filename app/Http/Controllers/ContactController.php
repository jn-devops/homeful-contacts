<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Homeful\Contacts\Models\Contact as ModelsContact;
use Homeful\Contacts\Models\Customer;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact): \Homeful\Contacts\Classes\ContactMetaData
    {
        return $contact->getData();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        //
    }

    /**
     * For Internal Testing Purposes. Will delete soon
     * TODO: Delete this before deployment
     */
    public function updateContactUsingId($email, Request $request)
    {
        try {
            $customer = Customer::where('email', $email)->first();
            if ($customer) {
                $data = $request->data;
                $customer->first_name = $data['first_name'];
                $customer->last_name = $data['last_name'];
                $customer->email = $data['email'];
                $customer->mobile = $data['mobile'];
                $customer->middle_name = $data['middle_name'];
                $customer->civil_status = $data['civil_status'];
                $customer->sex = $data['sex'];
                $customer->nationality = $data['nationality'];
                $customer->date_of_birth = $data['date_of_birth'];
                $customer->save();
        
                $this->addressUpdate($customer, $data['addresses']);
                $this->employmentUpdate($customer, $data['employment']);
                return response()->json([
                    'success' => true, 
                    'message' => 'Successfully Saved the Data', 
                    'data' => ['email' => $email, 'data' => $request->data]
                ], 200);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found'
                ], 404);
                
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500)
            ->header('Content-Type', 'text/plain');
        }
        
    }

    public function addressUpdate(Customer $contact, Array $data){
        $record_addresses = collect($contact->addresses)->mapWithKeys(function ($item) {
                return [$item['type'] => $item];
            })->toArray() ?? [];
        $data_addresses = collect($data)->mapWithKeys(function ($item) {
                return [$item['type'] => $item];
            })->toArray() ?? [];

        foreach($data_addresses as $add){
            $record_addresses[$add['type']] = $add;
        }
        
        $address_records = [];
        foreach($record_addresses as $type => $address_record){
            $address_records [] = $address_record;
        }

        $customer = Customer::find($contact->id);
        $customer->update(['addresses' => $address_records]);
        $customer->save();
    }

    public function employmentUpdate(Customer $contact, Array $data){
        $customer = Customer::find($contact->id);
        $customer->update(['employment' => $data]);
        $customer->save();
    }
}
