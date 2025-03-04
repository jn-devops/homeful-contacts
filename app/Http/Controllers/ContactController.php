<?php

namespace App\Http\Controllers;

use App\Actions\RegisterContact;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\User;
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
    public function destroy($email)
    {
        try {
            $user = User::where('email', $email)->first();
            if ($user) {
                if ($user->contact) {
                    $user->contact->delete();
                }
                $user->delete();
            
                return response()->json([
                    'success' => true,
                    'message' => 'User deleted successfully'
                ]);
            } else {
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

    /**
     * For Internal Testing Purposes. Will delete soon
     * TODO: Delete this before deployment
     */
    public function updateContactUsingId(Request $request)
    {
        try {
            $data = $request->data;
            $data_to_send = [
                "name" => $data['first_name'].' '.$data['last_name'],
                "email" => $data['email'],
                "mobile" => $data['mobile'],
                "password" => 'password',
                "password_confirmation" => 'password',
                "date_of_birth" => $data['date_of_birth'],
                "monthly_gross_income" => $data['employment'][0]['monthly_gross_income'],
            ];
            $user_data = app(RegisterContact::class)->run($data_to_send);
            $customer = Customer::find($user_data->contact->id);
            
            if ($customer) {
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
                
                if(isset($data['addresses'])){
                    $this->addressUpdate($customer, $data['addresses']);
                }
                if(isset($data['employment'])){
                    $this->employmentUpdate($customer, $data['employment']);
                }
                if(isset($data['spouse'])){
                    $this->spouseUpdate($customer, $data['spouse']);
                }
                if(isset($data['co_borrowers'])){
                    $this->coborrowerUpdate($customer, $data['co_borrowers']);
                }
                return response()->json([
                    'success' => true, 
                    'message' => 'Successfully Saved the Data', 
                    'data' => $request->data
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
    public function spouseUpdate(Customer $contact, Array $data){
        $customer = Customer::find($contact->id);
        $customer->update(['spouse' => $data]);
        $customer->save();
    }
    public function coborrowerUpdate(Customer $contact, Array $data){
        $customer = Customer::find($contact->id);
        $customer->update(['co_borrowers' => $data]);
        $customer->save();
    }
}
