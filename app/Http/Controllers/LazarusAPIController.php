<?php

namespace App\Http\Controllers;

use App\Events\ContactRegistered;
use App\Helper\GetAttachmentRequirement;
use App\Helper\WelcomeSMS;
use App\Models\User;
use App\Notifications\RegistrationWelcomeNotificationForSellerApp;
use App\Notifications\SendContactReferenceCodeNotification;
use Homeful\Contacts\Classes\ContactMetaData;
use Carbon\Carbon;
use Exception;
use FrittenKeeZ\Vouchers\Models\VoucherEntity;
use Homeful\Contacts\Models\Contact;
use Homeful\Contacts\Models\Customer;
use Homeful\References\Facades\References;
use Homeful\References\Models\Reference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

    public function updateContactFromLazarus(Request $request){
        try {
            $validated = $request->validate([
                'id' => 'required',
                'data' => 'required',
            ]);

            $data = $this->convertLazarusToContactData($validated['data']);
            // dd($data);
            $contact = Contact::where('id', $validated['id'])->update($data);
            if($contact){
                return response()->json([
                    'success' => true,
                    'message' => 'Contact Updated Successfully',
                    'data' => Contact::find($validated['id']),
                ], 200);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Something Went Wrong',
                    'errors' => 'Failed in updating the contacts.'
                ], 500);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong',
                'errors' => $e->getMessage()
            ], 500);
        }

    }

    public function setLazarusContact(Request $request){
        try {
            $validated = $request->validate([
                'contact_id' => 'required|string',
                'reference_code' => 'required|string',
                'project_code' => 'nullable|string',
                'source_of_sale' => 'nullable|string',
                'preferred_project' => 'nullable|string',
                'seller_voucher_code' => 'nullable|string',
                'campaign_author' => 'nullable|string',
                'referral_code' => 'nullable|string',
                'seller_code' => 'nullable|string',
                'seller_name' => 'nullable|string',
            ]);

            $data = Customer::find($validated['contact_id']);

            if(!($data instanceof Customer)){
                return response()->json([
                    'success' => false,
                    'message' => "No Contact Found",
                    'data' => [],
                ], 404);
            }
            $homeful_id = VoucherEntity::where('entity_id', $validated['contact_id'])->first()->voucher->code ?? null;
            $params = [
                'data' => $this->convertContactToLazarus($data, $validated['reference_code'], $validated['project_code'] ?? null),
            ];
            $response = Http::withToken(config('homeful-contacts.lazarus_api_token'))
                            ->post(config('homeful-contacts.lazarus_url').'api/contact/create', $params);
            // dd(config('homeful-contacts.lazarus_api_token'), config('homeful-contacts.lazarus_url').'api/contact/create', $params);
            if($response->successful()){
                // Update the Lazarus Data with homeful_id
                $lazarus_id = $response->json()['data']['id'];
                $lazarus_data = [
                    'order' => $response->json()['data']['order'],
                ];
                $lazarus_data['order']['homeful_id'] = $homeful_id;
                if($validated['project_code']){
                    $lazarus_data['order']['project_code'] = $validated['project_code'];
                }
                if($validated['source_of_sale']){
                    $lazarus_data['order']['source_of_sale'] = $validated['source_of_sale'];
                }
                if($validated['preferred_project']){
                    $lazarus_data['order']['preferred_project'] = $validated['preferred_project'];
                }
                if($validated['seller_voucher_code']){
                    $lazarus_data['order']['seller_voucher_code'] = $validated['seller_voucher_code'];
                }
                if($validated['campaign_author']){
                    $lazarus_data['order']['campaign_author'] = $validated['campaign_author'];
                }
                if($validated['referral_code']){
                    $lazarus_data['order']['referral_code'] = $validated['referral_code'];
                }
                if($validated['seller_code']){
                    $lazarus_data['order']['seller']['id'] = $validated['seller_code'];
                    $lazarus_data['order']['seller']['name'] = $validated['seller_name'];
                    $lazarus_data['order']['seller']['unit'] = null;
                    $lazarus_data['order']['seller']['superior'] = null;
                    $lazarus_data['order']['seller']['team_head'] = null;
                    $lazarus_data['order']['seller']['chief_seller_officer'] = null;
                }
                $lazarus_array_data = [
                    "data" => $lazarus_data,
                ];
                $lazarus_api_contact_update = Http::withToken(config('homeful-contacts.lazarus_api_token'))
                                ->put(config('homeful-contacts.lazarus_url').'api/contact/update/'.$lazarus_id, $lazarus_array_data);
                logger('Set Lazarus Status: '. $lazarus_api_contact_update->successful());
                return response()->json([
                    'success' => true,
                    'message' => 'Successfully Created Lazarus Data',
                    'data' => $response->json()['data'],
                ], 200);
            }else{
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
        $address_primary = collect($data['addresses'] ?? [])->where('type', 'primary')->first() ?? [];
        $address_present = collect($data['addresses'] ?? [])->where('type', 'present')->first() ?? [];
        $address_permanent = collect($data['addresses'] ?? [])->where('type', 'permanent')->first() ?? [];
        $address_co_borrower = collect($data['addresses'] ?? [])->where('type', 'co_borrower')->first() ?? [];
        $address_secondary = collect($data['addresses'] ?? [])->where('type', 'secondary')->first() ?? [];

        $employment = collect($data['employment'] ?? [])->where('type', 'buyer')->first() ?? [];
        $employment_co_borrower = collect($data['employment'] ?? [])->where('type', 'co_borrower')->first() ?? [];

        // Special Case for Employment
        if(is_numeric($employment['employer']['industry'] ?? '')){
            $employer_industry = $this->getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/work-industries?per_page=1000', 'code', $employment['employer']['industry'] ?? null, 'description') ?? null;
        }else{
            $employer_industry = $employment['employer']['industry'];
        }

        if(is_numeric($employment['employment_status'] ?? '')){
            $employment_status = $this->getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/employment-statuses?per_page=1000', 'code', $employment['employment_status'] ?? null, 'description') ?? null;
        }else{
            $employment_status = $employment['employment_status'];
        }

        if(is_numeric($employment['employment_type'] ?? '')){
            $employment_type = $this->getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/employment-types?per_page=1000', 'code', $employment['employment_type'] ?? null, 'description') ?? null;
        }else{
            $employment_type = $employment['employment_type'];
        }

        $customer_array = [
            'first_name' => $data['first_name'] ?? '',
            'middle_name' => $data['middle_name'] ?? '',
            'last_name' => $data['last_name'] ?? '',
            'name_suffix' => (($name_suffix = $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/name-suffixes?filter[code]='.($data['name_suffix'] ?? '-'), pure_data:true) ?? '')[0]['code'] != '001') ? $name_suffix[0]['description'] : '',
            'email' => $data['email'] ?? '',
            'mobile' => ($data['mobile']) ? '0'.$data['mobile'] : '',
            'civil_status' => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/civil-statuses?filter[code]='.($data['civil_status'] ?? '-'), pure_data:true)[0]['description'] ?? '001',
            'sex' => $data['sex'] ?? null,
            'nationality' => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/nationalities?filter[code]='.($data['nationality'] ?? '-'), pure_data:true)[0]['description'] ?? '076',
            'date_of_birth' => Carbon::parse($data['date_of_birth'] ?? '')->format('Y-m-d'),
            'mothers_maiden_name' => $data['mothers_maiden_name'] ?? '',
            'other_mobile' => $data['other_mobile'] ?? '',
            'help_number' => $data['help_number'] ?? '',
            'landline' => $data['landline'] ?? '',
            'addresses' => [
                [
                    'type' => 'Primary',
                    'ownership' => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/home-ownerships?filter[code]='.($address_primary['ownership'] ?? '-'), pure_data: true)[0]['description'] ?? 'Unknown',
                    'address1' => $address_primary['address1'] ?? '',
                    'sublocality' => $address_primary['sublocality'] ?? '',
                    'locality' => $address_primary['locality'] ?? '',
                    'administrative_area' => $address_primary['administrative_area'],
                    'postal_code' => $address_primary['postal_code'] ?? '',
                    'region' => $address_primary['region'] ?? '',
                    'country' => $address_primary['country'] ?? '',
                ],
                [
                    'type' => 'Present',
                    'ownership' => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/home-ownerships?filter[code]='.($address_present['ownership'] ?? '-'), pure_data: true)[0]['description'] ?? 'Unknown',
                    'address1' => $address_present['address1'] ?? '',
                    'sublocality' => $address_present['sublocality'] ?? '',
                    'locality' => $address_present['locality'] ?? '',
                    'administrative_area' => $address_present['administrative_area'],
                    'postal_code' => $address_present['postal_code'] ?? '',
                    'region' => $address_present['region'] ?? '',
                    'country' => $address_present['country'] ?? '',
                ],
                [
                    'type' => 'Permanent',
                    'ownership' => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/home-ownerships?filter[code]='.($address_permanent['ownership'] ?? '-'), pure_data: true)[0]['description'] ?? 'Unknown',
                    'address1' => $address_permanent['address1'] ?? '',
                    'sublocality' => $address_permanent['sublocality'] ?? '',
                    'locality' => $address_permanent['locality'] ?? '',
                    'administrative_area' => $address_permanent['administrative_area'],
                    'postal_code' => $address_permanent['postal_code'] ?? '',
                    'region' => $address_permanent['region'] ?? '',
                    'country' => $address_permanent['country'] ?? '',
                ],
            ],
            'employment' => [
                [
                    'type' => 'Primary',
                    'employment_status' => $employment_status,
                    'monthly_gross_income' => $employment['monthly_gross_income'] ?? 0,
                    'current_position' => $employment['current_position'] ?? null,
                    'employment_type' => $employment_type ?? null,
                    'rank' => $employment['rank'],
                    'years_in_service' => $employment['years_in_service'],
                    'employer' => [
                        'name' => $employment['employer']['name'] ?? '',
                        'industry' => $employer_industry,
                        'nationality' => $employment['employer']['nationality'] ?? null,
                        'address' => [
                            'type' => 'Primary',
                            'ownership' => 'Unknown',
                            'address1' => $employment['employer']['address']['address1'] ?? '',
                            'sublocality' => $employment['employer']['address']['sublocality'] ?? '',
                            'locality' => $employment['employer']['address']['locality'] ?? '',
                            'administrative_area' => $employment['employer']['address']['administrative_area'] ?? '',
                            'postal_code' => $employment['employer']['address']['postal_code'] ?? null,
                            'region' => $employment['employer']['address']['region'],
                            'country' => $employment['employer']['address']['country'],
                        ],
                        'contact_no' => $employment['employer']['contact_no'] ?? null,
                        'email' => $employment['employer']['email'] ?? null,
                        'year_established' => $employment['employer']['year_established'] ?? null,
                    ],
                    'id' => [
                        'tin' => $employment['id']['tin'] ?? '--',
                        'pagibig' => $employment['id']['pagibig'],
                        'sss' => $employment['id']['sss'],
                        'gsis' => $employment['id']['gsis'],
                    ],
                ],
            ],
        ];
        if(isset($data['co_borrowers']) && !empty($data['co_borrowers'])){
            $counter = 1;
            foreach($data['co_borrowers'] as $co_borrower){
                if($counter == 1){ // For Primary Co-Borrower Only
                    $customer_array['co_borrowers'][] = [
                        "sex" => $co_borrower['sex'],
                        "name" => $co_borrower['name'],
                        "type" => ($counter == 1) ? "Primary" : "Secondary",
                        "email" => $co_borrower['email'],
                        "mobile" => '0'.$co_borrower['mobile'],
                        "relation" => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/relationships?filter[code]='.($co_borrower['relationship_to_buyer'] ?? '-'), pure_data: true)[0]['description'] ?? '',
                        "last_name" => $co_borrower['last_name'],
                        "middle_name" => $co_borrower['middle_name'] ?? null,
                        "first_name" => $co_borrower['first_name'],
                        "nationality" => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/nationalities?filter[code]='.($co_borrower['nationality'] ?? '-'), pure_data:true)[0]['description'] ?? '076',
                        "civil_status" => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/civil-statuses?filter[code]='.($co_borrower['civil_status'] ?? '-'), pure_data:true)[0]['description'] ?? '001',
                        "date_of_birth" => $co_borrower['date_of_birth'],
                        "mothers_maiden_name" => $co_borrower['mothers_maiden_name'],
                        "addresses" => [
                            [
                                "type" => "Primary",
                                "region" => $address_co_borrower['region'] ?? '',
                                "country" => $address_co_borrower['country'] ?? "PH",
                                "address1" => $address_co_borrower['address1'] ?? '',
                                "locality" => $address_co_borrower['locality'] ?? '',
                                "ownership" => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/home-ownerships?filter[code]='.($address_co_borrower['ownership'] ?? '-'), pure_data: true)[0]['description'] ?? 'Unknown',
                                "postal_code" => $address_co_borrower['postal_code'] ?? '',
                                "sublocality" => $address_co_borrower['sublocality'] ?? '',
                                "administrative_area" => $address_co_borrower['administrative_area'] ?? ''
                            ],
                        ],
                        "employment" => [
                            [
                                "id" => [
                                    "tin" => $employment_co_borrower['id']['tin'] ?? '---',
                                    "pagibig" => $employment_co_borrower['id']['pagibig'] ?? null,
                                    "sss" => $employment_co_borrower['id']['sss'] ?? null,
                                    "gsis" => $employment_co_borrower['id']['gsis'] ?? null,
                                ],
                                "rank" => $employment_co_borrower['rank'] ?? '',
                                "type" => "Primary",
                                "employment_type" => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/employment-types?filter[code]='.($employment_co_borrower['employment_type'] ?? '-'), pure_data: true)[0]['description'] ?? '001',
                                "current_position" => $employment_co_borrower['current_position'] ?? null,
                                "years_in_service" => $employment_co_borrower['years_in_service'] ?? null,
                                "employment_status" => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/employment-statuses?filter[code]='.($employment_co_borrower['employment_status'] ?? '-'), pure_data: true)[0]['description'] ?? '001',
                                "monthly_gross_income" => $employment_co_borrower['monthly_gross_income'] ?? null,
                                "employer" => [
                                    "name" => $employment_co_borrower['employer']['name'] ?? null,
                                    "email" => $employment_co_borrower['employer']['email'] ?? null,
                                    "industry" => $this->getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/work-industries?per_page=1000', 'code', $employment_co_borrower['employer']['industry'] ?? null, 'description') ?? null,
                                    "contact_no" => '0'.$employment_co_borrower['employer']['contact_no'] ?? '',
                                    "nationality" => $this->getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/nationalities?per_page=1000', 'code', $employment_co_borrower['employer']['nationality'] ?? null, 'description') ?? null,
                                    "year_established" => $employment_co_borrower['employer']['year_established'] ?? null,
                                    "total_number_of_employees" => $employment_co_borrower['employer']['total_number_of_employees'] ?? null,
                                    "address" => [
                                        "type" => "Work",
                                        "region" => $employment_co_borrower['employer']['address']['region'],
                                        "country" => $employment_co_borrower['employer']['address']['country'],
                                        "address1" => $employment_co_borrower['employer']['address']['address1'] ?? '',
                                        "locality" => $employment_co_borrower['employer']['address']['locality'] ?? '',
                                        "ownership" => 'Unknown',
                                        "sublocality" => $employment_co_borrower['employer']['address']['sublocality'] ?? '',
                                        "administrative_area" => $employment_co_borrower['employer']['address']['administrative_area'] ?? '',
                                    ],
                                ],
                            ],
                        ],
                    ];
                    // If Co-borrower is Married
                    if($customer_array['co_borrowers'][0]['civil_status'] == 'Married'){
                        $customer_array['co_borrowers'][0]['spouse'] = [
                            "sex" => ($co_borrower['sex'] == 'Male') ? 'Female' : 'Male',
                            'name_suffix' => ($co_borrower['spouse']['name_suffix'] != '001') ? (($co_borrower['spouse']['name_suffix'] != '001') ? ($this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/name-suffixes?filter[code]='.($co_borrower['spouse']['name_suffix'] ?? '-'), pure_data: true)[0]['description'] ?? null) : '') : null ,
                            "first_name" => $co_borrower['spouse']['first_name'],
                            "middle_name" => $co_borrower['spouse']['middle_name'],
                            "last_name" => $co_borrower['spouse']['last_name'],
                            "nationality" => "Filipino",
                            "civil_status" => "Married",
                            "date_of_birth" => null,
                            "employment" => [
                                [
                                    "id" => [
                                        "tin" => $co_borrower['spouse']['tin'] ?? '--',
                                    ],
                                    "type" => "Primary",
                                    "employment_status" => "Regular",
                                    "monthly_gross_income" => 10000
                                ]
                            ],
                        ];
                    }
                }
                $counter += 1;
            }
        }
        
        if(isset($data['order']['aif']) && !empty($data['order']['aif'])){
            $customer_array['aif'] = [
                "sex" => $data['order']['aif']['sex'] ?? 'Male',
                "tin" => $data['order']['aif']['tin'] ?? '',
                // "name" => $data['order']['aif']['sex'] ?? 'Male',
                "email" => $data['order']['aif']['email'] ?? null,
                // "author" => $data['order']['aif']['sex'] ?? 'Male',
                "mobile" => '0'.$data['order']['aif']['mobile'] ?? '--',
                "landline" => $data['order']['aif']['landline'] ?? '',
                "last_name" => $data['order']['aif_attorney_last_name'] ?? '.',
                "first_name" => $data['order']['aif_attorney_first_name'] ?? '.',
                "middle_name" => $data['order']['aif_attorney_middle_name'] ?? null,
                "name_suffix" => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/name-suffixes?filter[code]='.($data['order']['aif_attorney_name_suffix'] ?? '-'), pure_data: true)[0]['description'] ?? null,
                "nationality" => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/nationalities?filter[code]='.($data['order']['aif']['nationality'] ?? '-'), pure_data:true)[0]['description'] ?? 'Filipino',
                "civil_status" => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/civil-statuses?filter[code]='.($data['order']['aif']['civil_status'] ?? '-'), pure_data:true)[0]['description'] ?? 'Single',
                "other_mobile" => $data['order']['aif']['other_mobile'] ?? null,
                "date_of_birth" => $data['order']['aif']['date_of_birth'] ?? '--',
                "relationship_to_buyer" => $data['order']['aif']['relationship_to_buyer'] ?? null,
            ];
        }

        return $customer_array;
    }

    private function getMaintenanceData($link, $column_name = null, $pure_data = false){
        if($pure_data){
            $response = Http::withToken(config('homeful-contacts.lazarus_api_token'))
                                ->get($link)->json()['data'] ?? null;
        }else{
            $response = Http::withToken(config('homeful-contacts.lazarus_api_token'))
                                ->get($link)->json()['data'][$column_name] ?? null;
        }
        return $response;
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

    private function convertContactToLazarus($data, $reference_code, $project_code = null){
        $param = [
            "homeful_contact_id" => $data->id,
            "reference_code" => $reference_code,
            "first_name" => $data->first_name,
            "middle_name" => $data->middle_name,
            "last_name" => $data->last_name,
            "name_suffix" => $this->getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/name-suffixes', 'name', $data->name_suffix, 'code') ?? '001',
            "civil_status" => $this->getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/civil-statuses', 'description', $data->civil_status ?? null, 'code') ?? '001',
            "sex" => $data->sex ?? "Male",
            "nationality" => $this->getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/nationalities?per_page=1000', 'description', $data->nationality ?? null, 'code') ?? '076',
            "date_of_birth" => $data->date_of_birth->format('Y-m-d'),
            "email" => $data->email,
            "mobile" => substr($data->mobile, 1),
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
                    "ownership" => $this->getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/home-ownerships?per_page=1000', 'description', collect($data->addresses)->where('type', 'Present')->first()['ownership'] ?? null, 'code') ?? '001',
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
                    "ownership" => $this->getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/home-ownerships?per_page=1000', 'description', collect($data->addresses)->where('type', 'Permanent')->first()['ownership'] ?? null, 'code') ?? '001',
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
                    "ownership" => $this->getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/home-ownerships?per_page=1000', 'description', collect($data->addresses)->where('type', 'Primary')->first()['ownership'] ?? null, 'code') ?? '001',
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
                    "rank" => collect($data->employment)->where('type', 'Primary')->first()['rank'] ?? '',
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
                        "industry" => $this->getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/work-industries?per_page=1000', 'description', collect($data->employment)->where('type', 'Primary')->first()['employer']['industry'] ?? null, 'code') ?? null,
                        "contact_no" => collect($data->employment)->where('type', 'Primary')->first()['employer']['contact_no'] ?? '',
                        "nationality" => $this->getMaintenanceDataCode(
                                                config('homeful-contacts.lazarus_url').'api/admin/nationalities?per_page=1000',
                                                'description',
                                                collect($data->employment)->where('type', 'Primary')->first()['employer']['nationality'] ?? '',
                                                'code'
                                            ) ?? '',
                        "year_established" => collect($data->employment)->where('type', 'Primary')->first()['employer']['year_established'] ?? '',
                        "total_number_of_employees" => null
                    ],
                    "industry" => $this->getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/work-industries?per_page=1000', 'description', collect($data->employment)->where('type', 'Primary')->first()['employer']['industry'] ?? null, 'code') ?? null,
                    "employment_type" => collect($data->employment)->where('type', 'Primary')->first()['employment_type'] ?? '',
                    "current_position" => collect($data->employment)->where('type', 'Primary')->first()['current_position'] ?? '',
                    "years_in_service" => collect($data->employment)->where('type', 'Primary')->first()['years_in_service'] ?? '',
                    "employment_status" => collect($data->employment)->where('type', 'Primary')->first()['employment_status'] ?? '',
                    "character_reference" => [],
                    "monthly_gross_income" => collect($data->employment)->where('type', 'Primary')->first()['monthly_gross_income'] ?? 0
                ]
            ],
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
                'government_id_type' => 'string',
            ]);

            $customer = Customer::find($validated['contact_id']);
            if($customer instanceof Customer){
                $name = $validated['attachment_name'];
                if($customer->$name){
                    $customer->$name->delete();
                }
                $customer->$name = $validated['url'];

                // Save the Government ID Type if available
                if(isset($validated['government_id_type'])){
                    $order = $customer->order;
                    if(is_array($order)){
                        $order['government_id_1_type'] = $validated['government_id_type'];
                    }else{
                        $order = ['government_id_1_type' => $validated['government_id_type']];
                    }
                    $customer->order = $order;
                    $customer->save();
                }

                $this->updateDocumentInLazarus($customer->id);

                return response()->json([
                    'success' => true,
                    'message' => 'Successfully Saved Data',
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

    public function getContactByVoucherCode(Request $request){
        $reference = Reference::where('code',$request->code)->first();
        $contact_id = $reference->voucherEntities()->where('entity_type','App\Models\Contact')->first()->entity_id;
        $contact =  Customer::findOrFail($contact_id);

        $contact=  $this->convertContactToLazarus($contact, $request->code, null, null);
        return response()->json([
            'success' => true,
            'data' =>$contact,
        ], 200);
    }
    public function createContact(Request $request){
        $data = $request->contact_data;

        $contact=\App\Models\Contact::where('email', $data['email'])
            ->where('mobile', '0'.$data['mobile']);
        if($contact->exists()){
            try {
                $contact = $contact->first();
                $user = User::where('contact_id', $contact->id)->first();
                $reference = Reference::withOwner($user)->first();
    
                $order = $contact->order;
                $order['homeful_id']=$reference->code;
                $contact->reference_code = $reference->code;
                $contact->order = $order;
                $contact->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Retrieved Existing Homeful ID',
                    'data' => $contact,
                ], 200);
            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot generate Homeful ID due to duplicate of data. Please contact support. '.$th->getMessage(),
                    'data' => null,
                ], 200);
            }
            
        }else{
            try {
                $user_model = User::where('email', $data['email'])
                    ->where('mobile', $data['mobile']);
                $password = config('homeful-contacts.default_password');
                if($user_model->exists()){
                    $user = $user_model->first();
                }else{
                    $password = Str::random(8);
                    $user = app(User::class)->create([
                        'name' => $data['first_name'].' '.$data['last_name'],
                        'email' => $data['email'],
                        'mobile' => $data['mobile'],
                        'password'=> Hash::make($password)
                    ]);
                }
                $converted = $this->convertLazarusToContactData($data);
                $contact = app(\App\Models\Contact::class)->create($converted);
                $user->contact()->associate($contact);
    
                $user->contact_id = $contact->id;
                $user->mobile = $data['mobile'];
                $user->save();
    
                $reference = References::withOwner($user)->create();
                $reference->addEntities($contact);
    
                $order = $contact->order;
                $order['homeful_id']=$reference->code;
                $contact->reference_code = $reference->code;
                $contact->order = $order;
                $contact->save();
    
                // event(new ContactRegistered($reference));
                $user->notify(new RegistrationWelcomeNotificationForSellerApp($reference, $password));
                WelcomeSMS::send($reference, $password);

                return response()->json([
                    'success' => true,
                    'message' => 'Successfully Created Contact',
                    'data' => $contact,
                ], 200);
            } catch (\Throwable $th) {
                throw $th;
                return response()->json([
                    'success' => false,
                    'message' => 'There seems to be an error in creating the contact. Data might be incomplete or invalid. Please contact support.',
                    'data' => null,
                ]);
            }
        }
    }

    public function updateDocumentInLazarus($contact_id){
        $response = Http::withToken(config('homeful-contacts.lazarus_api_token'))
                                ->get(config('homeful-contacts.lazarus_url').'contact/get-by-buyers-id/'.$contact_id);
        $attachments = $this->getAttachmentRequirementByID($contact_id);
        if($response->successful()){
            $lazarus_id = $response->json()['data']['id'];
            // $order = json_decode($response->json()['data']['order'] ?? '[]', true) ?? [];
            $order = $response->json()['data']['order'] ?? [];
            $order['attachments'] = $attachments->getData(true)['data'];
            $lazarus_data = [];
            $lazarus_data['order'] = $order;

            $lazarus_data['order']['aif'] = [
                "sex" => $order['sex'] ?? 'Male',
                "email" => $order['email'] ?? '-',
                "mobile" => $order['mobile'] ?? '-',
                "landline" => $order['landline'] ?? '-',
                "last_name" => $order['last_name'] ?? '-',
                "first_name" => $order['first_name'] ?? '-',
                "middle_name" => $order['middle_name'] ?? '-',
                "name_suffix" => $order['name_suffix'] ?? '-',
                "nationality" => $order['nationality'] ?? '-',
                "civil_status" => $order['civil_status'] ?? '-',
                "other_mobile" => $order['other_mobile'] ?? '-',
                "date_of_birth" => $order['date_of_birth'] ?? '-',
                "mothers_maiden_name" => $order['mothers_maiden_name'] ?? '-'
            ];
            $lazarus_api_contact_update = Http::withToken(config('homeful-contacts.lazarus_api_token'))
                                ->put(config('homeful-contacts.lazarus_url').'api/contact/update/'.$lazarus_id, ['data' => $lazarus_data]);
            if($lazarus_api_contact_update->successful()){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
