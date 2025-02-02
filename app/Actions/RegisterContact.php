<?php

namespace App\Actions;

use Homeful\Contacts\Enums\{CivilStatus, Employment, EmploymentStatus, Nationality, Sex, Suffix};
use Illuminate\Validation\{Rule, Rules, ValidationException};
use Illuminate\Support\Facades\{Hash, Validator};
use Homeful\Contacts\Classes\ReferenceMetadata;
use Homeful\References\Facades\References;
use App\Models\{Contact, Reference, User};
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Homeful\Contacts\Classes\Dummy;
use App\Events\ContactRegistered;
use Illuminate\Support\Arr;
use App\Data\UserData;
use App\Rules\Income;

class RegisterContact
{
    use AsAction;

    protected User $user;

    public Reference $reference;

    protected function register(array $validated): User
    {
        //hash the validated password, a Laravel best practice
        //as per documentation the hashed password stored in the database will be compared with the password value
        // passed to the Auth::attempt method via the array
        $validated['password'] = Hash::make($validated['password']);

        //pull the gmi from the list of attributes for processing
        //it is not needed in the persisting of the contact
        //but will be used later in the employment
        $gmi = (float) Arr::pull($validated, 'monthly_gross_income');

        if (!Arr::get($validated, 'name')) {
            Arr::set($validated, 'name', $validated['first_name'] . ' ' . $validated['last_name']);
        }
        //persist the user using the validated attributes sans the gmi attribute
        $user = app(User::class)->create($validated);

        //persist the contact model
        $attributes = array_merge((array) UserData::from($user), $validated);
        Arr::pull($validated, 'name');
        $contact = app(Contact::class)->create($attributes);

        //if the gmi attribute is valid then add it to the contact model
        //a default TIN is applied
        if ($gmi > 0.0) {
            $contact->employment = [
                [
                    'type' => Employment::default(),
                    'monthly_gross_income' => $gmi,
                    'employment_status' => EmploymentStatus::default(),
                    'id' => [
                        'tin' => Dummy::TIN
                    ]
            ]];
            $contact->save();
        }
        //associate the contact with the user
        $user->contact()->associate($contact);
        $user->save();

        //create a reference for the contact
        //and temporary save it to $this->reference class property for further processing
        $this->reference = References::withOwner($user)->create();
        $this->reference->addEntities($contact);

        event(new ContactRegistered($this->reference));

        //return the user, not the reference
        //it needs to be authorized in the registration controller
        return $user;
    }

    /**
     * @throws ValidationException
     */
    public function handle(array $attribs): ?User
    {
        $validator = Validator::make($attribs, $this->rules());

        return $validator->passes() ? $this->register($validator->validated()) : null;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['nullable', 'string', 'max:50'],
            'middle_name' => ['nullable', 'string', 'max:50'],
            'last_name' => ['nullable', 'string', 'max:50'],
            'name' => [
                'string',
                'max:255',
                'regex:/^([\w.]+) (.*)$/',
                'required_if:first_name,null,last_name,null'
            ],
            'name_suffix' => ['nullable', Rule::enum(Suffix::class)],
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'mobile' => 'required|string|max:11|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'civil_status' => ['nullable', Rule::enum(CivilStatus::class)],
            'sex' => ['nullable', Rule::enum(Sex::class)],
            'nationality' => ['nullable', Rule::enum(Nationality::class)],
            'mothers_maiden_name' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'monthly_gross_income' => ['nullable', 'numeric', new Income],
            'addresses' => ['nullable', 'array'],
            'employment' => ['nullable', 'array'],
            'spouse' => ['nullable', 'array'],
            'co_borrowers' => ['nullable', 'array'],
            'aif' => ['nullable', 'array'],
        ];
    }

    public function asController(ActionRequest $request): void
    {
        $this->register($request->validated());
    }

    public function jsonResponse(): ReferenceMetadata
    {
        return ReferenceMetadata::from($this->reference);
    }

    public function getReference(): Reference
    {
        return $this->reference;
    }
}
