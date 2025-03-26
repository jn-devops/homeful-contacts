<?php

namespace App\Actions;

use Homeful\Contacts\Models\Contact;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UpdateLoanProcessingContactData
{
    public static function updateContact($id){
        try {
            if($loan_processing_id = self::getLoanProcessingDataID($id)){
                $homeful_contact = Contact::find($id);
                $data = self::convertContactToLazarus($homeful_contact);
                // dd($data);
                $response = Http::withToken(config('homeful-contacts.lazarus_api_token'))
                                ->put(config('homeful-contacts.lazarus_url').'api/admin/contacts/'.$loan_processing_id, $data);
                if($response->successful()){
                    return true;
                }else{
                    Log::error('Failed Loan Processing API', ['api'=> config('homeful-contacts.lazarus_url').'api/admin/contacts/'.$loan_processing_id, 'data' => $data]);
                    return false;
                }
            }else{
                Log::error('No Loan Processing Data Found with Contact ID: '. $id);
                return false;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    protected static function getLoanProcessingDataID($contact_id){
        $response = Http::withToken(config('homeful-contacts.lazarus_api_token'))
                        ->get(config('homeful-contacts.lazarus_url').'api/admin/contacts?filter[homeful_contact_id]='.$contact_id);

        return ($response->json()['data']) ? ($response->json()['data'][0]['id'] ?? null) : null;
    }

    protected static function convertContactToLazarus(Contact $data){
        $param = [
            "homeful_contact_id" => $data->id,
            // "reference_code" => $data->reference_code,
            "first_name" => $data->first_name,
            "middle_name" => $data->middle_name,
            "last_name" => $data->last_name,
            "name_suffix" => self::getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/name-suffixes', 'description', $data->name_suffix, 'code') ?? '001',
            "civil_status" => self::getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/civil-statuses', 'description', $data->civil_status ?? null, 'code') ?? '001',
            "sex" => $data->sex ?? "Male",
            "nationality" => self::getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/nationalities?per_page=1000', 'description', $data->nationality ?? null, 'code') ?? '076',
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
                    "ownership" => self::getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/home-ownerships?per_page=1000', 'description', collect($data->addresses)->where('type', 'Present')->first()['ownership'] ?? null, 'code') ?? '001',
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
                    "ownership" => self::getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/home-ownerships?per_page=1000', 'description', collect($data->addresses)->where('type', 'Permanent')->first()['ownership'] ?? null, 'code') ?? '001',
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
                    "ownership" => self::getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/home-ownerships?per_page=1000', 'description', collect($data->addresses)->where('type', 'Primary')->first()['ownership'] ?? null, 'code') ?? '001',
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
                    "rank" =>  collect($data->employment)->where('type', 'Primary')->first()['rank'] ?? '',
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
                        "nationality" => self::getMaintenanceDataCode(
                                                config('homeful-contacts.lazarus_url').'api/admin/nationalities?per_page=1000',
                                                'description',
                                                collect($data->employment)->where('type', 'Primary')->first()['employer']['nationality'] ?? '',
                                                'code'
                                            ) ?? '',
                        "year_established" => collect($data->employment)->where('type', 'Primary')->first()['employer']['year_established'] ?? '',
                        "total_number_of_employees" => null
                    ],
                    "industry" => collect($data->employment)->where('type', 'Primary')->first()['employer']['industry'] ?? '',
                    "employment_type" => collect($data->employment)->where('type', 'Primary')->first()['employment_type'] ?? '',
                    "current_position" => collect($data->employment)->where('type', 'Primary')->first()['current_position'] ?? '',
                    "years_in_service" => collect($data->employment)->where('type', 'Primary')->first()['years_in_service'] ?? '',
                    "employment_status" => collect($data->employment)->where('type', 'Primary')->first()['employment_status'] ?? '',
                    "character_reference" => [],
                    "monthly_gross_income" => collect($data->employment)->where('type', 'Primary')->first()['monthly_gross_income'] ?? 0
                ]
            ],
            "co_borrowers" => null
        ];
        return $param;
    }

    protected static function getMaintenanceDataCode($link, $description_column_name, $description, $column_name){
        if($description){
            $response = collect(Http::withToken(config('homeful-contacts.lazarus_api_token'))
                                ->get($link)->json()['data'] ?? []);
            $returned_desc = optional($response->where($description_column_name, $description)->first())[$column_name] ?? null;
            return $returned_desc;
        }else{
            return null;
        }

    }

}
