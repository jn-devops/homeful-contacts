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
            if($loan_processing_data = self::getLoanProcessingDataID($id)){
                $loan_processing_id = $loan_processing_data['id'] ?? null;
                $homeful_contact = Contact::find($id);
                $data = self::convertContactToLazarus($homeful_contact, $loan_processing_data['order']);
                $response = Http::withToken(config('homeful-contacts.lazarus_api_token'))
                                ->put(config('homeful-contacts.lazarus_url').'api/admin/contacts/'.$loan_processing_id, $data);
                if($response->successful()){
                    Log::error('Success Loan Processing API', ['api'=> config('homeful-contacts.lazarus_url').'api/admin/contacts/'.$loan_processing_id, 'data' => $data]);
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
        return ($response->json()['data']) ? ($response->json()['data'][0] ?? null) : null;
    }

    protected static function convertContactToLazarus(Contact $data, $loan_processing = null){
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
                        "industry" => self::getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/work-industries?per_page=1000', 'description', collect($data->employment)->where('type', 'Primary')->first()['employer']['industry'] ?? null, 'code') ?? null,
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
                    "industry" => self::getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/work-industries?per_page=1000', 'description', collect($data->employment)->where('type', 'Primary')->first()['employer']['industry'] ?? null, 'code') ?? null,
                    "employment_type" => self::getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/employment-types?per_page=1000', 'description', collect($data->employment)->where('type', 'Primary')->first()['employment_type'] ?? null, 'code') ?? '001',
                    "current_position" => collect($data->employment)->where('type', 'Primary')->first()['current_position'] ?? '',
                    "years_in_service" => collect($data->employment)->where('type', 'Primary')->first()['years_in_service'] ?? '',
                    "employment_status" => self::getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/employment-statuses?per_page=1000', 'description', collect($data->employment)->where('type', 'Primary')->first()['employment_status'] ?? null, 'code') ?? '002',
                    "character_reference" => [],
                    "monthly_gross_income" => collect($data->employment)->where('type', 'Primary')->first()['monthly_gross_income'] ?? 0
                ]
            ],
            "co_borrowers" => null,
        ];

        // Check if there is a coborrower
        if($data->co_borrowers){
            $co_borrower = [];
            $co_borrower_addresses = null;
            $co_borrower_employment = null;
            if(isset($data->co_borrowers[0]['addresses'])){
                $co_borrower_addresses = [
                    "type" => "co_borrower",
                    "block" => null,
                    "region" => collect($data->co_borrowers[0]['addresses'])->where('type', 'Primary')->first()['region'] ?? '',
                    "street" => null,
                    "country" => collect($data->co_borrowers[0]['addresses'])->where('type', 'Primary')->first()['country'] ?? 'PH',
                    "address1" => collect($data->co_borrowers[0]['addresses'])->where('type', 'Primary')->first()['address1'] ?? '',
                    "locality" => collect($data->co_borrowers[0]['addresses'])->where('type', 'Primary')->first()['locality'] ?? '',
                    "ownership" => self::getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/home-ownerships?per_page=1000', 'description', collect($data->co_borrowers[0]['addresses'])->where('type', 'Primary')->first()['ownership'] ?? null, 'code') ?? '001',
                    "postal_code" => collect($data->co_borrowers[0]['addresses'])->where('type', 'Primary')->first()['postal_code'] ?? '',
                    "sublocality" => collect($data->co_borrowers[0]['addresses'])->where('type', 'Primary')->first()['sublocality'] ?? '',
                    "full_address" => "",
                    "administrative_area" => collect($data->co_borrowers[0]['addresses'])->where('type', 'Primary')->first()['administrative_area'] ?? ''
                ];
            }
            if(isset($data->co_borrowers[0]['employment'])){
                $co_borrower_employment = [
                    "id" => [
                        "sss" => $data->co_borrowers[0]['employment'][0]['id']['sss'] ?? '',
                        "tin" => $data->co_borrowers[0]['employment'][0]['id']['tin'] ?? '--',
                        "gsis" => $data->co_borrowers[0]['employment'][0]['id']['gsis'] ?? '',
                        "pagibig" => $data->co_borrowers[0]['employment'][0]['id']['pagibig'] ?? '',
                    ],
                    "rank" => $data->co_borrowers[0]['employment'][0]['rank'] ?? '',
                    "type" => "co_borrower",
                    "employer" => [
                        "fax" => $data->co_borrowers[0]['employment'][0]['employer']['fax'] ?? null,
                        "name" => $data->co_borrowers[0]['employment'][0]['employer']['name'] ?? '',
                        "email" => $data->co_borrowers[0]['employment'][0]['employer']['email'] ?? '',
                        "address" => [
                            "type" => "company",
                            "region" => $data->co_borrowers[0]['employment'][0]['employer']['address']['region'] ?? '',
                            "country" => $data->co_borrowers[0]['employment'][0]['employer']['address']['country'] ?? 'PH',
                            "address1" => $data->co_borrowers[0]['employment'][0]['employer']['address']['address1'] ?? '',
                            "locality" => $data->co_borrowers[0]['employment'][0]['employer']['address']['locality'] ?? '',
                            "ownership" => $data->co_borrowers[0]['employment'][0]['employer']['address']['ownership'] ?? '',
                            "sublocality" => $data->co_borrowers[0]['employment'][0]['employer']['address']['sublocality'] ?? '',
                            "full_address" => $data->co_borrowers[0]['employment'][0]['employer']['address']['full_address'] ?? '',
                            "administrative_area" => $data->co_borrowers[0]['employment'][0]['employer']['address']['administrative_area'] ?? '',
                        ],
                        "industry" => self::getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/work-industries?per_page=1000', 'description', $data->co_borrowers[0]['employment'][0]['employer']['industry'] ?? null, 'code') ?? null,
                        "contact_no" => ($data->co_borrowers[0]['employment'][0]['employer']['contact_no']) ? substr($data->co_borrowers[0]['employment'][0]['employer']['contact_no'], 1) : '',
                        "nationality" => self::getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/nationalities?per_page=1000', 'description', $data->co_borrowers[0]['employment'][0]['employer']['nationality'] ?? null, 'code') ?? '076',
                        "year_established" => $data->co_borrowers[0]['employment'][0]['employer']['year_established'] ?? '', // Missing
                        "total_number_of_employees" => $data->co_borrowers[0]['employment'][0]['employer']['total_number_of_employees'] ?? null,
                    ],
                    "industry" => null,
                    "employment_type" => self::getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/employment-types', 'description', $data->co_borrowers[0]['employment'][0]['industry'] ?? null, 'code') ?? '001',
                    "current_position" => $data->co_borrowers[0]['employment'][0]['current_position'] ?? null,
                    "years_in_service" => $data->co_borrowers[0]['employment'][0]['years_in_service'] ?? '',
                    "employment_status" => self::getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/employment-statuses', 'description', $data->co_borrowers[0]['employment'][0]['employment_status'] ?? null, 'code') ?? '001',
                    "character_reference" => [
                        "name" => "",
                        "mobile" => "",
                    ],
                    "monthly_gross_income" => $data->co_borrowers[0]['employment'][0]['monthly_gross_income'] ?? '0',
                ];
            }
            $co_borrower = [
                [
                    "sex" => $data->co_borrowers[0]['sex'] ?? 'Male',
                    "name" => $data->co_borrowers[0]['name'] ?? '',
                    "email" => $data->co_borrowers[0]['email'] ?? '',
                    "mobile" => ($data->co_borrowers[0]['mobile']) ? substr($data->co_borrowers[0]['mobile'], 1) : '',
                    "spouse" => [
                        "sex" => $data->co_borrowers[0]['spouse']['sex'] ?? null,
                        "tin" => $data->co_borrowers[0]['spouse']['employment'][0]['id']['tin'] ?? null,
                        "email" => $data->co_borrowers[0]['spouse']['email'] ?? null,
                        "mobile" => $data->co_borrowers[0]['spouse']['mobile'] ?? null,
                        "landline" => $data->co_borrowers[0]['spouse']['landline'] ?? null,
                        "last_name" => $data->co_borrowers[0]['spouse']['last_name'] ?? null,
                        "first_name" => $data->co_borrowers[0]['spouse']['first_name'] ?? null,
                        "middle_name" => $data->co_borrowers[0]['spouse']['middle_name'] ?? null,
                        "name_suffix" => self::getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/name-suffixes', 'description', $data->co_borrowers[0]['spouse']['name_suffix'] ?? null, 'code') ?? '001',
                        "nationality" => self::getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/nationalities?per_page=1000', 'description', $data->co_borrowers[0]['spouse']['nationality'] ?? null, 'code') ?? '076',
                        "civil_status" => self::getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/civil-statuses', 'description', $data->co_borrowers[0]['spouse']['civil_status'] ?? null, 'code') ?? '001',
                        "date_of_birth" => $data->co_borrowers[0]['spouse']['date_of_birth'] ?? null,
                        "mothers_maiden_name" => $data->co_borrowers[0]['spouse']['mothers_maiden_name'] ?? null
                    ],
                    "passport" => $data->co_borrowers[0]['passport'] ?? null,
                    "last_name" => $data->co_borrowers[0]['last_name'] ?? null,
                    "first_name" => $data->co_borrowers[0]['first_name'] ?? null,
                    "date_issued" => $data->co_borrowers[0]['date_issued'] ?? null,
                    "help_number" => $data->co_borrowers[0]['help_number'] ?? null,
                    "middle_name" => $data->co_borrowers[0]['middle_name'] ?? null,
                    "name_suffix" => self::getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/name-suffixes', 'description', $data->co_borrowers[0]['name_suffix'] ?? null, 'code') ?? '001',
                    "nationality" => self::getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/nationalities?per_page=1000', 'description', $data->co_borrowers[0]['nationality'] ?? null, 'code') ?? '076',
                    "civil_status" => self::getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/civil-statuses', 'description', $data->co_borrowers[0]['civil_status'] ?? null, 'code') ?? '001',
                    "other_mobile" => $data->co_borrowers[0]['other_mobile'] ?? '',
                    "place_issued" => $data->co_borrowers[0]['place_issued'] ?? '',
                    "date_of_birth" => $data->co_borrowers[0]['date_of_birth'] ?? '',
                    "mothers_maiden_name" => $data->co_borrowers[0]['mothers_maiden_name'] ?? '',
                    "relationship_to_buyer" => self::getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/maintenance/relationships?per_page=1000', 'description', $data->co_borrowers[0]['relation'] ?? null, 'code') ?? null,
                    "relation" => self::getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/maintenance/relationships?per_page=1000', 'description', $data->co_borrowers[0]['relation'] ?? null, 'code') ?? null,
                ]
            ];
            
            $param["co_borrowers"] = $co_borrower;
            if($co_borrower_addresses){
                $param["addresses"][] = $co_borrower_addresses;
            }
            if($co_borrower_employment){
                $param["employment"][] = $co_borrower_employment;
            }
        }
        if($data->aif){
            $param['order'] = $loan_processing;
            $param['order']['aif'] = $data->aif;
            $param['order']['aif_attorney_first_name'] = $data->aif['first_name'] ?? '';
            $param['order']['aif_attorney_last_name'] = $data->aif['last_name'] ?? '';
            $param['order']['aif_attorney_middle_name'] = $data->aif['middle_name'] ?? '';
            $param['order']['aif_attorney_name_suffix'] = self::getMaintenanceDataCode(config('homeful-contacts.lazarus_url').'api/admin/name-suffixes', 'description', $data->aif['name_suffix'] ?? null, 'code') ?? '001';
            // Special Condition:
            $param['order']['aif']['middle_name'] = $data->aif['middle_name'] ?? '-';
            $param['order']['aif']['name_suffix'] = $param['order']['aif_attorney_name_suffix'];
            $param['order']['aif']['email'] = $data->aif['email'] ?? '-';
            $param['order']['aif']['mobile'] = ($data->aif['mobile']) ? substr($data->aif['mobile'] ?? '--', 1) : '--';
            $param['order']['aif']['date_of_birth'] = $data->aif['date_of_birth'] ?? '--';

        }

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
