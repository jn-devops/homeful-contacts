<?php

namespace App\Imports;

use Homeful\Contacts\Classes\{AddressMetadata, AIFMetadata, CoBorrowerMetadata, EmployerMetadata, EmploymentMetadata, IdMetadata, SpouseMetadata};
use Maatwebsite\Excel\Concerns\{SkipsOnError, SkipsOnFailure, ToModel, WithBatchInserts, WithHeadingRow, WithProgressBar, WithSkipDuplicates};
use Homeful\Contacts\Enums\{AddressType, CivilStatus, CoBorrowerType, Employment, EmploymentStatus, EmploymentType};
use Homeful\Contacts\Enums\{Industry, Nationality, Ownership, Position, Relation, Sex, Suffix, Tenure};
use Maatwebsite\Excel\Concerns\{Importable, SkipsErrors, SkipsFailures};
use Illuminate\Support\{Arr, Carbon, Str};
use Spatie\LaravelData\DataCollection;
use App\Actions\RegisterContact;

class UsersImport implements ToModel, WithHeadingRow, WithBatchInserts, SkipsOnError, SkipsOnFailure, WithSkipDuplicates, WithProgressBar
{
    use SkipsFailures;
    use SkipsErrors;
    use Importable;

    public static int $counter = 1000;

    public function __construct(protected RegisterContact $action){}

    public function model(array $row)
    {
        $attribs = array_filter([
            'first_name' => $this->extractFirstName($row),
            'middle_name' => $this->extractMiddleName($row),
            'last_name' => $this->extractLastName($row),
            'name_suffix' => $this->extractNameSuffix($row),
            'email' => $this->extractEmail($row),
            'mobile' => $this->extractMobile($row),
            'password' => $password = $this->generatePassword(),
            'password_confirmation' => $password,
            'civil_status' => $this->extractCivilStatus($row),
            'sex' => $this->extractSex($row),
            'nationality' => $this->extractNationality($row),
            'mothers_maiden_name' => $this->extractMothersMaidenName($row),
            'date_of_birth' => $this->extractDateOfBirth($row),
            'addresses' => $this->extractAddresses($row),
            'spouse' => $this->extractSpouse($row),
            'employment' => $this->extractEmployment($row, 'buyer'),
            'co_borrowers' => $this->extractCoBorrowers($row),
            'aif' => $this->extractAIF($row)
        ]);

        return $this->action->run($attribs);
    }

    protected function extractFirstName(array $row, string $key = 'first_name'): string
    {
        $first_name = (string) Arr::get($row, $key);

        return Str::title($first_name);
    }

    protected function extractMiddleName(array $row, string $key = 'middle_name'): string
    {
        $middle_name = (string) Arr::get($row, $key);

        return Str::title($middle_name);
    }

    protected function extractLastName(array $row, string $key = 'last_name'): string
    {
        $last_name = (string) Arr::get($row, $key);

        return Str::title($last_name);
    }

    protected function extractNameSuffix(array $row, string $key = 'name_suffix'): string
    {
        $name_suffix = (string) Arr::get($row, $key);

        return Suffix::tryFrom(Str::title($name_suffix))?->value ?? Suffix::tryFromCode($name_suffix)->value;
    }

    protected function extractEmail(array $row, string $key = 'email'): string
    {
        $email = (string)  Arr::get($row, $key);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $email = Str::random(5) . '.537@gmail.com';

        return Str::lower($email);
    }

    protected function extractMobile(array $row, string $key = 'mobile'): string
    {
        $mobile = (string) Arr::get($row, $key);
        try {
            $mobile = phone($mobile, 'PH')->formatForMobileDialingInCountry('PH');
        } catch (\Exception $exception) {
            $mobile = '0917537' . self::$counter++; //hallucinate
        }

        return $mobile;
    }

    protected function extractCivilStatus(array $row, string $key = 'civil_status'): string
    {
        $civil_status = (string) Arr::get($row, $key);

        return CivilStatus::tryFrom(Str::title($civil_status))?->value ?? CivilStatus::tryFromCode($civil_status)->value;
    }

    protected function extractSex(array $row, string $key = 'sex'): string
    {
        $sex = (string) Arr::get($row, $key);

        return Sex::tryFrom(Str::title($sex))?->value ?? Sex::MALE->value;
    }

    protected function extractNationality(array $row, string $key = 'nationality'): string
    {
        $nationality = (string) Arr::get($row, $key);

        return Nationality::tryFrom(Str::title($nationality))?->value ?? Nationality::tryFromCode($nationality)->value;
    }

    protected function extractMothersMaidenName(array $row, string $key = 'mothers_maiden_name'): string
    {
        $mothers_maiden_name = (string) Arr::get($row, $key);

        return Str::title($mothers_maiden_name);
    }

    protected function extractDateOfBirth(array $row, string $key = 'date_of_birth'): string
    {
        $date_of_birth = (string) Arr::get($row, $key);

        return Carbon::parse($date_of_birth)->format('Y-m-d');
    }

    protected function extractAddresses(array $row, $key = 'addresses'): array
    {
        $addresses = json_decode(Arr::get($row, $key), true);
        foreach ($addresses as &$address) $this->transformAddress($address);
        unset($address);

        return (new DataCollection(AddressMetadata::class, $addresses))->toArray();
    }

    protected function extractEmployment(array $row, string $key): array
    {
        if (empty($employment = Arr::get($this->extractEmployments($row), $key))) return [];

        $this->toTitleFieldValues($employment, ['rank']);
        $employment['type'] = Employment::default()->value;
        $employment['industry'] = $this->extractIndustry($employment);
        $employment['employment_type'] = $this->extractEmploymentType($employment);
        $employment['current_position'] = $this->extractPosition($employment);
        $employment['years_in_service'] = $this->extractTenure($employment);
        $employment['employment_status'] = $this->extractEmploymentStatus($employment);
        $employment['monthly_gross_income'] = (float) $employment['monthly_gross_income'];
        $employment['employer'] = $this->extractEmployer($employment);
        $employment['id'] = $this->extractId($employment);

        return (new DataCollection(EmploymentMetadata::class, [$employment]))->toArray();
    }

    protected function extractSpouse(array $row, string $key = 'spouse'): array
    {
        if (empty($spouse = $this->extractJson($row, $key))) return [];
        $this->toTitleFieldValues($spouse, ['name', 'first_name', 'middle_name', 'last_name', 'name_suffix', 'mothers_maiden_name', 'sex']);
        $spouse['name_suffix'] = $this->extractNameSuffix($spouse);
        $spouse['civil_status'] = $this->extractCivilStatus($spouse);
        $spouse['sex'] = Sex::tryFrom($this->extractSex($row))?->other() ?? $this->extractSex($spouse);
        $spouse['nationality'] = $this->extractNationality($spouse);
        $spouse['email'] = $this->extractEmail($spouse);
        $spouse['mobile'] = $this->extractMobile($spouse);

        return SpouseMetadata::from($spouse)->toArray();
    }

    protected function extractCoBorrowers(array $row, string $key = 'co_borrowers'): array
    {
        if (empty($co_borrowers = $this->extractJson($row, $key))) return [];

        $co_borrower = $co_borrowers[0];

        $this->toTitleFieldValues($co_borrower, ['name', 'first_name', 'middle_name', 'last_name', 'mothers_maiden_name', 'sex']);
        $co_borrower['name_suffix'] = $this->extractNameSuffix($co_borrower);
        $co_borrower['type'] = $this->extractCoBorrowerType($co_borrower);
        $co_borrower['email'] = $this->extractEmail($co_borrower);
        $co_borrower['mobile'] = $this->extractMobile($co_borrower);
        $co_borrower['nationality'] = $this->extractNationality($co_borrower);
        $co_borrower['civil_status'] = $this->extractCivilStatus($co_borrower);
        $co_borrower['spouse']  = $this->extractSpouse($co_borrower);
        $co_borrower['employment'] = $this->extractEmployment($row, 'co_borrower');
        $co_borrower['relation']  = $this->extractRelation($co_borrower, 'relationship_to_buyer');

        return (new DataCollection(CoBorrowerMetadata::class, [$co_borrower]))->toArray();
    }

    protected function extractAIF(array $row, string $key = 'order.aif'): array
    {
        if (empty($aif = array_filter($this->extractJson($row, $key)))) return [];

        $aif['first_name'] = $this->extractFirstName($aif);
        $aif['middle_name'] = $this->extractMiddleName($aif);
        $aif['last_name'] = $this->extractLastName($aif);
        $aif['name_suffix'] = $this->extractNameSuffix($aif);
        $aif['nationality'] = $this->extractNationality($aif);
        $aif['civil_status'] = $this->extractCivilStatus($aif);
        $aif['date_of_birth'] = $this->extractDateOfBirth($aif);
        $aif['mothers_maiden_name'] = $this->extractMothersMaidenName($aif);
        $aif['mobile'] = $this->extractMobile($aif);
        $aif['email'] = $this->extractEmail($aif);
        $aif['sex'] = $this->extractSex($aif);

        return rescue(fn() => AIFMetadata::from($aif)->toArray(), []);
    }

    private function extractJson(array $array, string $keys): array
    {
        $record = Arr::get($array, dot_shift($keys)); if (!$record) return [];
        $payload = is_array($record) ? $record : json_decode($record, true) ?? [];

        return array_filter(empty($keys) ? $payload : Arr::get($payload, $keys, []));
    }

    private function toTitleFieldValues(array &$array, array $fields): void
    {
        array_walk($fields, function ($field) use (&$array) {
            $array[$field] = Str::title($array[$field] ?? '');
        });
    }

    private function extractEmployments(array $row, string $key = 'employment'): array
    {
        if (empty($employments = $this->extractJson($row, $key))) return [];

        return array_reduce($employments, function ($carry, $item) {
            $carry[$item['type']] = $item;
            return $carry;
        }, []);
    }

    private function extractIndustry(array $array, $key = 'industry'): string
    {
        $industry = (string) Arr::get($array, $key);

        return Industry::tryFrom($industry)?->value ?? Industry::tryFromCode($industry)->value;
    }

    private function extractEmploymentType(array $array, string $key = 'employment_type'): string
    {
        $employment_type = (string) Arr::get($array, $key);

        return EmploymentType::tryFrom(Str::title($employment_type))?->value ?? EmploymentType::tryFromCode($employment_type)->value;
    }

    private function extractPosition(array $array, string $key = 'current_position'): string
    {
        $position = (string) Arr::get($array, $key);

        return Position::tryFrom($position)?->value ?? Position::tryFromCode($position)->value;
    }

    private function extractTenure(array $array, string $key = 'years_in_service'): string
    {
        $tenure = (string) Arr::get($array, $key);

        return Tenure::tryFrom($tenure)?->value ?? Tenure::tryFromCode($tenure)->value;
    }

    private function extractEmploymentStatus(array $array, string $key = 'employment_status'): string
    {
        $employment_status = (string) Arr::get($array, $key);

        return EmploymentStatus::tryFrom(Str::title($employment_status))?->value ?? EmploymentStatus::tryFromCode($employment_status)->value;
    }

    private function extractEmployer(array $array, string $key = 'employer'): array
    {
        if (empty($employer = $this->extractJson($array, $key))) return [];
        $this->toTitleFieldValues($employer, ['name']);
        $employer['email'] = $this->extractEmail($employer);
        $employer['nationality'] = $this->extractNationality($employer);
        $employer['industry'] = $this->extractIndustry($employer);
        $employer['address'] = $this->extractAddress($employer, 'address');

        return EmployerMetadata::from($employer)->toArray();
    }

    private function extractId(array $array, string $key = 'id'): array
    {
        if (empty($id = $this->extractJson($array, $key))) return [];

        return IdMetadata::from($id)->toArray();
    }

    private function extractAddress(array $array, string $key = 'address'): array
    {
        if (!$address = Arr::get($array, $key, [])) return [];
        $this->transformAddress($address);

        return AddressMetadata::from($address)->toArray();
    }

    private function transformAddress(array &$address): void
    {
        $this->toTitleFieldValues($address, ['address1']);
        $type = (string) Arr::get($address, 'type');
        $address['type'] = AddressType::tryFrom(Str::title($type))?->value ?? AddressType::tryFromCode($type)->value;
        $ownership = Arr::get($address, 'ownership');
        $address['ownership'] = Ownership::tryFrom(Str::title($ownership))?->value ?? Ownership::tryFromCode($ownership)->value;
        $address['postal_code'] = Arr::get($address, 'postal_code', '0000');
    }

    private function extractCoBorrowerType(array $row, string $key = 'type'): string
    {
        $co_borrower_type =  (string) Arr::get($row, $key);

        return CoBorrowerType::tryFrom(Str::title($co_borrower_type))?->value ?? CoBorrowerType::default()->value;
    }

    private function extractRelation(array $array, string $key): string
    {
        $relation = (string) Arr::get($array, $key);

        return Relation::tryFrom(Str::title($relation))?->value ?? Relation::tryFromCode($relation)->value;
    }

    private function generatePassword(): string
    {
        $password = config('homeful-contacts.default_password');

        return is_null($password) ? Str::password() : $password;
    }

    public function batchSize(): int
    {
        return 100;
    }
}
