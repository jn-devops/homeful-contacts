<?php

namespace App\Http\Controllers;

use App\Actions\RegisterContact;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Reference;
use App\Models\User;
use FrittenKeeZ\Vouchers\Models\Voucher;
use Homeful\Contacts\Models\Contact as ModelsContact;
use Homeful\Contacts\Models\Customer;
use Homeful\Contracts\Models\Contact as ContractsModelsContact;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

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
    public function destroy_email($email)
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
    public function destroy_mobile($mobile)
    {
        try {
            $user = User::where('mobile', $mobile)->first();
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

     public function getContactById(Request $request){
        $request->validate([
            'id' => 'required'
        ]);

        $contact = Contact::find($request->id);
        if($contact){
            return $contact;
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
                'data' => []
            ]);

        }

     }

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
                if(isset($data['aif'])){
                    $this->aifUpdate($customer, $data['aif']);
                }
                return response()->json([
                    'success' => true, 
                    'message' => 'Successfully Saved the Data', 
                    'data' => ContractsModelsContact::find($customer->id),
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
    public function aifUpdate(Customer $contact, Array $data){
        $customer = Customer::find($contact->id);
        $customer->update(['aif' => $data]);
        $customer->save();
    }

    // This is intended for order column only. Can update this to cater all field in the future.
    public function updateContactByHomefulId(Request $request){ 
        try {
            $validated = $request->validate([
                'homeful_id' => 'required',
                'data' => 'required|array',
                'data.order' => 'required|array',
            ]);
    
            $reference = Reference::where('code', $validated['homeful_id'])->first();
    
            if (!$reference) {
                throw new \Exception('Reference not found.');
            }
    
            $contact = $reference->getContact();
    
            if (!$contact) {
                throw new \Exception('Contact not found.');
            }
    
            $order = $contact->order ?? [];
            $order = array_merge($order, $validated['data']['order']);
    
            $contact->update(['order' => $order]);
    
            return response()->json([
                'success' => true,
                'message' => 'Successfully updated the contact data',
                'data' => $contact
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating contact data: ' . $e->getMessage());
    
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function checkReferralContact($homeful_id){
        try {
            $reference = Reference::where('code', $homeful_id)->first();
            if($reference && $contact = $reference->getContact()){
                
                if (
                    isset($contact->order) &&
                    is_array($contact->order)
                    // array_key_exists('referral_code', $contact->order)
                ){
                    return response()->json([
                        'success' => true,
                        'referral_code_exists' => true,
                        'referral_code' => $contact->order['referral_code'],
                        'message' => 'Contacts Not Found',
                    ]);
                }else{
                    return response()->json([
                        'success' => false,
                        'referral_code_exists' => false,
                        'referral_code' => null,
                        'message' => 'Contacts Not Found',
                    ]);
                }
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Contacts Not Found',
                    'referral_code_exists' => false,
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong with the data',
                'referral_code_exists' => false,
            ]);
        }
    }

    public function validate_email($email){
        $contact = Contact::where('email', $email)->first();
        if($contact){
            return response()->json(['success' => true, 'exists' => true], 200);
        }else{
            return response()->json(['success' => true, 'exists' => false], 200);
        }
    }

    public function validate_mobile($mobile){
        // $mobile_formatted = '0'.substr($mobile, 2);
        $contact = Contact::where('mobile', $mobile)->first();
        if($contact){
            return response()->json(['success' => true, 'exists' => true], 200);
        }else{
            return response()->json(['success' => true, 'exists' => false], 200);
        }
    }
}
