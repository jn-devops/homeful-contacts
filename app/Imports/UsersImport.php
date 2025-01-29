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

class UsersImport implements ToModel, WithHeadingRow, SkipsOnFailure, SkipsOnError
{
    use SkipsFailures, SkipsErrors;

    public static int $counter = 1000;

    public function __construct(protected RegisterContact $action){}

    public function model(array $row)
    {
        try {
            return $this->action->run([
                'name' => $this->extractName($row),
                'email' => $this->extractEmail($row),
                'mobile' => $this->extractMobile($row),
                'password' => 'password',
                'password_confirmation' => 'password',
                'date_of_birth' => $this->extractDateOfBirth($row),
                'monthly_gross_income' => $this->extractMonthlyGrossIncome($row),
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

    public function onFailure(Failure ...$failures)
    {

    }

    public function onError(Throwable $e)
    {

    }
}
