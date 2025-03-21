<?php

namespace App\Http\Controllers;

use App\Helper\GetAttachmentRequirement;
use App\Models\User;
use Homeful\Contacts\Classes\ContactMetaData;
use Homeful\Contacts\Models\Contact;
use Homeful\Contacts\Models\Customer;
use Homeful\References\Models\Reference;
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

    public function updateContactFromLazarus(Request $request){
        try {
            $validated = $request->validate([
                'data' => 'required',
            ]);

            $data = $this->convertLazarusToContactData($validated['data']);
            dd($data);

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

    public function setLazarusContact(Request $request){
        try {
            $validated = $request->validate([
                'contact_id' => 'required|string',
                'reference_code' => 'required|string',
            ]);

            $data = Customer::find($validated['contact_id']);

            if(!($data instanceof Customer)){
                return response()->json([
                    'success' => false,
                    'message' => "No Contact Found",
                    'data' => [],
                ], 404);
            }
            $params = $this->convertContactToLazarus($data, $validated['reference_code']);
            // return response()->json($params);

            $response = Http::withToken(config('homeful-contacts.lazarus_api_token'))
                            ->post(config('homeful-contacts.lazarus_url').'api/admin/contacts', $params);
            if($response->successful()){
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
        $address_work = collect($data['addresses'] ?? [])->where('type', 'work')->first() ?? [];
        $address_secondary = collect($data['addresses'] ?? [])->where('type', 'secondary')->first() ?? [];

        $employment = collect($data['employment'] ?? [])->where('type', 'buyer')->first() ?? [];
        // dd($employment['current_positison'] ?? '');

        $customer_array = [
            'first_name' => $data['first_name'] ?? '',
            'middle_name' => $data['middle_name'] ?? '',
            'last_name' => $data['last_name'] ?? '',
            'name_suffix' => (($name_suffix = $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/name-suffixes?filter[code]='.($data['name_suffix'] ?? '-'), pure_data:true) ?? '') != 'N/A') ? $name_suffix : '',
            'email' => $data['email'] ?? '',
            'mobile' => $data['mobile'] ?? '',
            'civil_status' => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/civil-statuses?filter[code]='.($data['civil_status'] ?? '-'), pure_data:true)[0]['description'] ?? '',
            'sex' => $data['sex'] ?? '',
            'nationality' => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/nationalities?filter[code]='.($data['nationality'] ?? '-'), pure_data:true)[0]['description'] ?? '',
            'date_of_birth' => $data['date_of_birth'] ?? '',
            'mothers_maiden_name' => $data['mothers_maiden_name'] ?? '',
            'addresses' => [
                [
                    'type' => 'Primary',
                    'ownership' => $address_primary['ownership'] ?? '',
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
                    'ownership' => $address_present['ownership'] ?? '',
                    'address1' => $address_present['address1'] ?? '',
                    'sublocality' => $address_present['sublocality'] ?? '',
                    'locality' => $address_present['locality'] ?? '',
                    'administrative_area' => $address_present['administrative_area'],
                    'postal_code' => $address_present['postal_code'] ?? '',
                    'region' => $address_present['region'] ?? '',
                    'country' => $address_present['country'] ?? '',
                ],
                [
                    'ownership' => $address_permanent['ownership'] ?? '',
                    'address1' => $address_permanent['address1'] ?? '',
                    'sublocality' => $address_permanent['sublocality'] ?? '',
                    'locality' => $address_permanent['locality'] ?? '',
                    'administrative_area' => $address_permanent['administrative_area'],
                    'postal_code' => $address_permanent['postal_code'] ?? '',
                    'region' => $address_permanent['region'] ?? '',
                    'country' => $address_permanent['country'] ?? '',
                ],
                // [
                //     'type' => 'Permanent',
                //     'ownership' => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/home-ownerships/'.$address_permanent['ownership'] ?? '-', 'description') ?? '',
                //     'address1' => $address_permanent['address1'] ?? '',
                //     'sublocality' => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/philippine-barangays?filter[barangay_code]='.$address_permanent['sublocality'] ?? '-', pure_data:true)[0]['barangay_description'] ?? '',
                //     'locality' => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/philippine-cities?filter[city_municipality_code]='.$address_permanent['locality'] ?? '-', pure_data:true)[0]['city_municipality_description'] ?? '',
                //     'administrative_area' => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/philippine-provinces?filter[province_code]='.$address_permanent['administrative_area'] ?? '-', pure_data:true)[0]['province_description'] ?? '',
                //     'postal_code' => $address_permanent['postal_code'] ?? '',
                //     'region' => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/philippine-regions?filter[region_code]='.$address_permanent['region'] ?? '-', pure_data:true)[0]['region_description'] ?? '',
                //     'country' => $address_permanent['country'] ?? '',
                // ],
            ],
            'employment' => [
                [
                    'type' => 'Primary',
                    'employment_status' => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/employment-statuses?filter[code]='.($employment['employment_status'] ?? '-'), pure_data:true)[0]['description'] ?? '',
                    'monthly_gross_income' => $employment['monthly_gross_income'] ?? 0,
                    'current_position' => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/current-positions?filter[code]='.($employment['current_position'] ?? '-'), pure_data:true)[0]['description'] ?? '',
                    'employment_type' => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/employment-types?filter[code]='.($employment['employment_type'] ?? '-'), pure_data:true)[0]['description'] ?? '',
                    'employer' => [
                        'name' => $employment['employer']['name'] ?? '',
                        'industry' => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/work-industries?filter[code]='.($employment['employer']['industry'] ?? '-'), pure_data:true)[0]['description'] ?? '',
                        'nationality' => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/nationalities?filter[code]='.($employment['employer']['nationality'] ?? '-'), pure_data:true)[0]['description'] ?? null,
                        'address' => [
                            'type' => 'Primary',
                            'ownership' => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/home-ownerships/'.($employment['employer']['address']['ownership'] ?? '-'), 'description') ?? '',
                            'address1' => $employment['employer']['address']['address1'] ?? '',
                            'sublocality' => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/philippine-barangays?filter[barangay_code]='.($employment['employer']['address']['sublocality'] ?? '-'), pure_data:true)[0]['barangay_description'] ?? '',
                            'locality' => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/philippine-cities?filter[city_municipality_code]='.($employment['employer']['address']['locality'] ?? '-'), pure_data:true)[0]['city_municipality_description'] ?? '',
                            'postal_code' => $employment['employer']['address']['postal_code'] ?? null,
                            'region' => $this->getMaintenanceData(config('homeful-contacts.lazarus_url').'api/admin/philippine-regions?filter[region_code]='.($employment['employer']['address']['region'] ?? '-'), pure_data:true)[0]['region_description'] ?? '',
                            'country' => $employment['employer']['address']['country'],
                        ],
                        'contact_no' => $employment['employer']['contact_no'] ?? null,
                    ],
                    'id' => [
                        'tin' => $employment['id']['tin'],
                        'pagibig' => $employment['id']['pagibig'],
                        'sss' => $employment['id']['sss'],
                        'gsis' => $employment['id']['gsis'],
                    ],
                ],
                // [
                //     'type' => Employment::SIDELINE->value,
                //     'employment_status' => EmploymentStatus::CONTRACTUAL->value,
                //     'monthly_gross_income' => (string) ($this->faker->numberBetween(12000, 25000) * 100),
                //     'current_position' => $this->faker->word(),
                //     'employment_type' => EmploymentType::LOCALLY_EMPLOYED->value,
                //     'employer' => [
                //         'name' => $this->faker->word(),
                //         'industry' => Industry::random()->value,
                //         'nationality' => Nationality::random()->value,
                //         'address' => [
                //             'type' => AddressType::PRIMARY->value,
                //             'ownership' => Ownership::random()->value,
                //             'address1' => $this->faker->address(),
                //             'sublocality' => $this->faker->city(),
                //             'locality' => $this->faker->city(),
                //             'postal_code' => $this->faker->postcode(),
                //             'region' => 'NCR',
                //             'country' => 'PH',
                //         ],
                //         'contact_no' => $this->faker->word(),
                //     ],
                //     'id' => [
                //         'tin' => $this->faker->word(),
                //         'pagibig' => $this->faker->word(),
                //         'sss' => $this->faker->word(),
                //         'gsis' => $this->faker->word(),
                //     ],
                // ],
            ],
        ];

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

    private function convertContactToLazarus($data, $reference_code){
        $param = [
            "homeful_contact_id" => $data->id,
            "reference_code" => $reference_code,
            "first_name" => $data->first_name,
            "middle_name" => $data->middle_name,
            "last_name" => $data->last_name,
            "name_suffix" => $this->getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/name-suffixes', 'name', $data->name_suffix, 'code') ?? '001',
            "civil_status" => $this->getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/civil-statuses', 'description', $data->civil_status?->value ?? null, 'code') ?? '001',
            "sex" => $data->sex?->value ?? "Male",
            "nationality" => $this->getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/nationalities?per_page=1000', 'description', $data->nationality?->value ?? null, 'code') ?? '076',
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
                                                config('homeful-contacts.lazarus_url').'api/admin/nationalities?per_page=1000',
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
        // dd($param);
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

    public function getContactByVoucherCode(Request $request){
        $reference = Reference::where('code',$request->code)->first();
        $contact_id = $reference->voucherEntities()->where('entity_type','App\Models\Contact')->first()->entity_id;
        $contact =  Customer::findOrFail($contact_id);
          $contact=  $this->convertContactToLazarus($contact, $request->code);
        return response()->json([
            'success' => true,
            'data' =>$contact,
        ], 200);
    }

}
