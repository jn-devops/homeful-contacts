<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\{Arr, Carbon, Str};
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\ToModel;
use Nette\Schema\ValidationException;
use App\Actions\RegisterContact;
use App\Models\User;
use Throwable;
use Spatie\LaravelData\DataCollection;
use Homeful\Contacts\Classes\AddressMetadata;
use Homeful\Contacts\Enums\{AddressType, EmploymentStatus, EmploymentType, Ownership};


class UsersImport implements ToModel, WithHeadingRow, SkipsOnFailure, SkipsOnError
{
    use SkipsFailures, SkipsErrors;

    public static int $counter = 1000;

    public function __construct(protected RegisterContact $action){}

    public function model(array $row)
    {
        try {
            return $this->action->run([
//                'name' => $this->extractName($row),
                'first_name' => $this->extractFirstName($row),
                'middle_name' => $this->extractMiddleName($row),
                'last_name' => $this->extractLastName($row),
                'name_suffix' => $this->extractNameSuffix($row),
                'email' => $this->extractEmail($row),
                'mobile' => $this->extractMobile($row),
                'password' => 'password',
                'password_confirmation' => 'password',
                'civil_status' => $this->extractCivilStatus($row),
                'sex' => $this->extractSex($row),
                'nationality' => $this->extractNationality($row),
                'mothers_maiden_name' => $this->extractMothersMaidenName($row),
                'date_of_birth' => $this->extractDateOfBirth($row),
                'monthly_gross_income' => $this->extractMonthlyGrossIncome($row),
                'addresses' => $this->extractAddresses($row)
            ]);
        } catch (\Exception $exception) {

        }
    }

    protected function extractName(array $row): string
    {
        $attribs = Arr::only($row, ['first_name', 'middle_name', 'last_name']);
        $name = __(':first_name :middle_name :last_name', $attribs);

        return Str::title($name);
    }

    protected function extractFirstName(array $row): string
    {
        $first_name = Arr::get($row, 'first_name');

        return Str::title($first_name);
    }

    protected function extractMiddleName(array $row): string
    {
        $middle_name = Arr::get($row, 'middle_name');

        return Str::title($middle_name);
    }

    protected function extractLastName(array $row): string
    {
        $last_name = Arr::get($row, 'last_name');

        return Str::title($last_name);
    }

    protected function extractNameSuffix(array $row): string
    {
        $name_suffix = Arr::get($row, 'name_suffix');

        return Str::title($name_suffix);
    }

    protected function extractEmail(array $row): string
    {
        $email = Arr::get($row, 'email');

        return Str::lower($email);
    }

    protected function extractMobile(array $row): string
    {
        $mobile = Arr::get($row, 'mobile');
        try {
            phone($mobile, 'PH')->formatForMobileDialingInCountry('PH');
        } catch (\Exception $exception) {
            $mobile = '0917123' . self::$counter++;
        }

        return $mobile;
    }

    protected function extractCivilStatus(array $row): string
    {
        $civil_status = Arr::get($row, 'civil_status');

        return Str::title($civil_status);
    }

    protected function extractSex(array $row): string
    {
        $sex = Arr::get($row, 'sex');

        return Str::title($sex);
    }

    protected function extractNationality(array $row): string
    {
        $nationality = Arr::get($row, 'nationality');

        return Str::title($nationality);
    }

    protected function extractMothersMaidenName(array $row): string
    {
        $mothers_maiden_name = Arr::get($row, 'mothers_maiden_name');

        return Str::title($mothers_maiden_name);
    }

    protected function extractDateOfBirth(array $row): string
    {
        $date_of_birth = Arr::get($row, 'date_of_birth');

        return Carbon::parse($date_of_birth)->format('Y-m-d');
    }

    protected function extractMonthlyGrossIncome(array $row): float
    {
        $employment = json_decode(Arr::get($row, 'employment'), true)[0];
        $monthly_gross_income  = Arr::get($employment, 'monthly_gross_income');

        return (float) $monthly_gross_income;
    }

    protected function extractAddresses(array $row): array
    {
        $addresses = json_decode(Arr::get($row, 'addresses'), true);

        foreach ($addresses as &$address) {

            $address['type'] = AddressType::tryFromCode($address['type']);
            $address['ownership'] = Ownership::tryFromCode($address['ownership']);
        }
        unset($item);
        $data = new DataCollection(AddressMetadata::class, $addresses);

        return $data->toArray();
    }

    public function onFailure(Failure ...$failures)
    {

    }

    public function onError(Throwable $e)
    {

    }
}
