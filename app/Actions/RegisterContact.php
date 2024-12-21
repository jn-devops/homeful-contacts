<?php

namespace App\Actions;

use App\Enums\{CivilStatus, Employment, EmploymentStatus, Nationality, Sex};
use Illuminate\Validation\{Rule, Rules, ValidationException};
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Support\Facades\Hash;
use App\Classes\EmploymentMetadata;
use App\Events\ContactRegistered;
use App\Models\{Contact, User};
use Illuminate\Support\Arr;
use App\Data\UserData;
use App\Rules\Income;

class RegisterContact
{
    use AsAction;

    protected User $user;

    protected function register(array $validated): User
    {
        $validated['password'] = Hash::make($validated['password']);
        $gmi = (float) Arr::pull($validated, 'monthly_gross_income');
        $user = app(User::class)->create($validated);
        $attributes = array_merge((array) UserData::from($user), $validated);
        $contact = app(Contact::class)->create($attributes);

        if ($gmi > 0.0) {
            $employment = EmploymentMetadata::from([
                'type' => Employment::default(),
                'monthly_gross_income' => $gmi,
                'employment_status' => EmploymentStatus::default()
            ]);
            $contact->employment = [$employment];
            $contact->save();
        }

        $user->contact()->associate($contact);
        $user->save();
        event(new ContactRegistered($user->contact));

        return $user;
    }

    /**
     * @throws ValidationException
     */
    public function handle(array $attribs): User
    {
        return $this->register(validator($attribs, $this->rules())->validated());
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'regex:/^(\w+) (.*)$/'],
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'mobile' => 'required|string|max:11',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'middle_name' => ['nullable', 'string'],
            'civil_status' => ['nullable', Rule::enum(CivilStatus::class)],
            'sex' => ['nullable', Rule::enum(Sex::class)],
            'nationality' => ['nullable', Rule::enum(Nationality::class)],
            'date_of_birth' => ['nullable', 'date'],
            'monthly_gross_income' => ['nullable', 'numeric', new Income],
        ];
    }

    public function asController(ActionRequest $request): void
    {
        $this->user = $this->register($request->validated());
    }

    public function jsonResponse(): UserData
    {
        return UserData::from($this->user);
    }
}
