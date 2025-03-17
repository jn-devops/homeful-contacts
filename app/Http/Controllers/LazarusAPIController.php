<?php

namespace App\Http\Controllers;

use App\Helper\GetAttachmentRequirement;
use App\Models\User;
use Homeful\Contacts\Models\Contact;
use Homeful\Contacts\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class LazarusAPIController extends Controller
{
    public function login(Request $request){
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
        
            $user = User::where('email', $request->email)->first();
            if($user instanceof User){
                if (!$user || !Hash::check($request->password, $user->password)) {
                    return response()->json(['message' => 'Invalid credentials'], 401);
                }
            
                return response()->json([
                    'token' => $user->createToken('api-token')->plainTextToken,
                ]);

            }else{
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

        }catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function getContactByID($id){
        $contact = Contact::find($id);
        if($contact instanceof Contact)
            return response()->json([
                'success' => true,
                'message' => 'Contact Found',
                'data' => $contact
            ], 200);
        else
            return response()->json([
                'success' => false,
                'message' => 'No Contact Found',
                'data' => []
            ], 404);
    }

    public function setContact(Request $request){
        try {
            $validated = $request->validate([
                'data' => 'required',
            ]);

            $data = $this->convertLazarusToContactData($validated['data']);
    
            return response()->json([
                'success' => true,
                'message' => 'No Contact Found',
                'data' => $validated['data'],
            ], 200);
    
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
        
    }

    public function setLazarusContact($id){
        try {
            $data = Customer::find($id);

            $params = $this->convertContactToLazarus($data);

            $response = Http::withToken(config('homeful-contacts.lazarus_api_token'))
                            ->post(config('homeful-contacts.lazarus_url').'/api/admin/contacts', $params);
            if($response->successful()){
                return response()->json([
                    'success' => true,
                    'message' => 'Successfully Created Lazarus Data',
                    'data' => $response->json()['data'],
                ], 200);
            }else{
                dd($response);
                return response()->json([
                    'success' => false,
                    'message' => $response->json()['message'] ?? 'Something went wrong',
                    'data' => [],
                ], 500);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    private function convertLazarusToContactData($data){

        dd($this->getMaintenanceDataDescription('http://homeful-lazarus.test/api/admin/civil-statuses/', $data['civil_status'], 'description'));
        
        Customer::create([
            'first_name' => $data['first_name'] ?? '',
            'middle_name' => $data['middle_name'] ?? '', 
            'last_name' => $data['last_name'] ?? '',
            'name_suffix' => $data['name_suffix'] ?? '',
            'email' => $data['email'] ?? '',
            'mobile' => $data['mobile'] ?? '',
            'civil_status' => '',
            'sex' => $data['sex'] ?? '',
            'nationality' => $data['nationality'] ?? '',
            'date_of_birth' => $data['date_of_birth'] ?? '',
            'mothers_maiden_name' => $data['mothers_maiden_name'] ?? '',
        ]);
    }

    private function getMaintenanceDataDescription($link, $code, $column_name){
        $response = collect(Http::withToken(config('homeful-contacts.lazarus_api_token'))
                            ->get($link)->json()['data'] ?? []);
        $returned_desc = optional($response->where('code', $code)->first())[$column_name] ?? '';
        return $returned_desc;

    }

    private function getMaintenanceDataCode($link, $description_column_name, $description, $column_name){
        if($description){
            $response = collect(Http::withToken(config('homeful-contacts.lazarus_api_token'))
                                ->get($link)->json()['data'] ?? []);
            $returned_desc = optional($response->where($description_column_name, $description)->first())[$column_name] ?? null;
            return $returned_desc;
        }else{
            return null;
        }

    }

    private function convertContactToLazarus($data){
        $param = [
            "homeful_contact_id" => $data->id,
            "reference_code" => $data->reference_code,
            "first_name" => $data->first_name,
            "middle_name" => $data->middle_name,
            "last_name" => $data->last_name,
            "name_suffix" => $this->getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'/api/admin/name-suffixes', 'name', $data->name_suffix, 'code') ?? '001',
            "civil_status" => $this->getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'/api/admin/civil-statuses', 'description', $data->civil_status->value, 'code') ?? '',
            "sex" => $data->sex->value,
            "nationality" => $this->getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'/api/admin/nationalities?per_page=1000', 'description', $data->nationality->value, 'code') ?? '',
            "date_of_birth" => $data->date_of_birth->format('Y-m-d'),
            "email" => $data->email,
            "mobile" => $data->mobile,
            "other_mobile" => $data->other_mobile,
            "help_number" => $data->help_number,
            "landline" => $data->landline,
            "mothers_maiden_name" => $data->mothers_maiden_name,
            "addresses" => [
                [
                    "type" => "present",
                    "block" => null,
                    "region" => collect($data->addresses)->where('type', 'Present')->first()['region'] ?? '',
                    "street" => null,
                    "country" => collect($data->addresses)->where('type', 'Present')->first()['country'] ?? 'PH',
                    "address1" => collect($data->addresses)->where('type', 'Present')->first()['address1'] ?? '',
                    "locality" => collect($data->addresses)->where('type', 'Present')->first()['locality'] ?? '',
                    "ownership" => $this->getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'/api/admin/home-ownerships?per_page=1000', 'description', $data->nationality->value, 'code') ?? '001',
                    "postal_code" => collect($data->addresses)->where('type', 'Present')->first()['postal_code'] ?? '',
                    "sublocality" => collect($data->addresses)->where('type', 'Present')->first()['sublocality'] ?? '',
                    "full_address" => "",
                    "administrative_area" => collect($data->addresses)->where('type', 'Present')->first()['administrative_area'] ?? ''
                ],
                [
                    "type" => "permanent",
                    "block" => null,
                    "region" => collect($data->addresses)->where('type', 'Permanent')->first()['region'] ?? '',
                    "street" => null,
                    "country" => collect($data->addresses)->where('type', 'Permanent')->first()['country'] ?? 'PH',
                    "address1" => collect($data->addresses)->where('type', 'Permanent')->first()['address1'] ?? '',
                    "locality" => collect($data->addresses)->where('type', 'Permanent')->first()['locality'] ?? '',
                    "ownership" => $this->getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'/api/admin/home-ownerships?per_page=1000', 'description', $data->nationality->value, 'code') ?? '001',
                    "postal_code" => collect($data->addresses)->where('type', 'Permanent')->first()['postal_code'] ?? '',
                    "sublocality" => collect($data->addresses)->where('type', 'Permanent')->first()['sublocality'] ?? '',
                    "full_address" => "",
                    "administrative_area" => collect($data->addresses)->where('type', 'Permanent')->first()['administrative_area'] ?? ''
                ],
                [
                    "type" => "primary",
                    "block" => null,
                    "region" => collect($data->addresses)->where('type', 'Primary')->first()['region'] ?? '',
                    "street" => null,
                    "country" => collect($data->addresses)->where('type', 'Primary')->first()['country'] ?? 'PH',
                    "address1" => collect($data->addresses)->where('type', 'Primary')->first()['address1'] ?? '',
                    "locality" => collect($data->addresses)->where('type', 'Primary')->first()['locality'] ?? '',
                    "ownership" => $this->getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'/api/admin/home-ownerships?per_page=1000', 'description', $data->nationality->value, 'code') ?? '001',
                    "postal_code" => collect($data->addresses)->where('type', 'Primary')->first()['postal_code'] ?? '',
                    "sublocality" => collect($data->addresses)->where('type', 'Primary')->first()['sublocality'] ?? '',
                    "full_address" => "",
                    "administrative_area" => collect($data->addresses)->where('type', 'Primary')->first()['administrative_area'] ?? ''
                ]
            ],
            "employment" => [
                [
                    "id" => [
                        "sss" => collect($data->employment)->where('type', 'Primary')->first()['id']['sss'] ?? '',
                        "tin" => collect($data->employment)->where('type', 'Primary')->first()['id']['tin'] ?? '',
                        "gsis" => collect($data->employment)->where('type', 'Primary')->first()['id']['gsis'] ?? '',
                        "pagibig" => collect($data->employment)->where('type', 'Primary')->first()['id']['pagibig'] ?? '',
                    ],
                    "rank" => "",
                    "type" => "buyer",
                    "employer" => [
                        "fax" => null,
                        "name" => collect($data->employment)->where('type', 'Primary')->first()['employer']['name'] ?? '',
                        "email" => collect($data->employment)->where('type', 'Primary')->first()['employer']['email'] ?? '',
                        "address" => [
                            "type" => "company",
                            "region" => collect($data->employment)->where('type', 'Primary')->first()['employer']['address']['region'] ?? '',
                            "country" => collect($data->employment)->where('type', 'Primary')->first()['employer']['address']['country'] ?? 'PH',
                            "address1" => collect($data->employment)->where('type', 'Primary')->first()['employer']['address']['address1'] ?? '',
                            "locality" => collect($data->employment)->where('type', 'Primary')->first()['employer']['address']['locality'] ?? '',
                            "ownership" => "001",
                            "sublocality" => collect($data->employment)->where('type', 'Primary')->first()['employer']['address']['sublocality'] ?? '',
                            "full_address" => collect($data->employment)->where('type', 'Primary')->first()['employer']['address']['short_address'] ?? '',
                            "administrative_area" => collect($data->employment)->where('type', 'Primary')->first()['employer']['address']['administrative_area'] ?? ''
                        ],
                        "industry" => collect($data->employment)->where('type', 'Primary')->first()['employer']['industry'] ?? '',
                        "contact_no" => collect($data->employment)->where('type', 'Primary')->first()['employer']['contact_no'] ?? '',
                        "nationality" => $this->getMaintenanceDataCode(
                                                config('homeful-contacts.lazarus_url').'/api/admin/nationalities?per_page=1000',
                                                'description', 
                                                collect($data->employment)->where('type', 'Primary')->first()['employer']['nationality'] ?? '', 
                                                'code'
                                            ) ?? '',
                        "year_established" => "",
                        "total_number_of_employees" => null
                    ],
                    "industry" => collect($data->employment)->where('type', 'Primary')->first()['employer']['industry'] ?? '',
                    "employment_type" => collect($data->employment)->where('type', 'Primary')->first()['employment_type'] ?? '',
                    "current_position" => collect($data->employment)->where('type', 'Primary')->first()['current_position'] ?? '',
                    "years_in_service" => "",
                    "employment_status" => collect($data->employment)->where('type', 'Primary')->first()['employment_status'] ?? '',
                    "character_reference" => [],
                    "monthly_gross_income" => collect($data->employment)->where('type', 'Primary')->first()['monthly_gross_income'] ?? 0
                ]
            ],
            "co_borrowers" => null
        ];

        return $param;
    }

    public function getAttachmentRequirementByID($id){
        return response()->json([
            'success' => true,
            'data' => GetAttachmentRequirement::getAttachmentByID($id),
        ]);
    }

    public function setAttachmentRequirementByID(Request $request){
        try {
            $validated = $request->validate([
                'contact_id' => 'required',
                'attachment_name' => 'required|string',
                'url' => 'required|string',
            ]);

            $customer = Customer::find($validated['contact_id']);
            if($customer instanceof Customer){
                $name = $validated['attachment_name'];
                $customer->$name = $validated['url'];

                return response()->json([
                    'success' => true,
                    'message' => 'Successfully Created Lazarus Data',
                    'data' => $customer,
                ], 200);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'No Contact Found',
                    'data' => [],
                ], 404);
            }
    
    
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }
    
}
