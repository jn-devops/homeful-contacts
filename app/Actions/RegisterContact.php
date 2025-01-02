<?php

namespace App\Actions;

use Homeful\Contacts\Enums\{CivilStatus, Employment, EmploymentStatus, Nationality, Sex};
use Homeful\Contacts\Classes\{EmploymentMetadata, ReferenceMetadata};
use Illuminate\Validation\{Rule, Rules, ValidationException};
use Homeful\References\Facades\References;
use App\Models\{Contact, Reference, User};
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Support\Facades\Hash;
use App\Events\ContactRegistered;
use Illuminate\Support\Arr;
use App\Data\UserData;
use App\Rules\Income;

class RegisterContact
{
    use AsAction;

    protected User $user;

    protected Reference $reference;

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

        //persist the user using the validated attributes sans the gmi attribute
        $user = app(User::class)->create($validated);

        //persist the contact model
        $attributes = array_merge((array) UserData::from($user), $validated);
        $contact = app(Contact::class)->create($attributes);

        //if the gmi attribute is valid then add it to the contact model
        if ($gmi > 0.0) {
            $employment = EmploymentMetadata::from([
                'type' => Employment::default(),
                'monthly_gross_income' => $gmi,
                'employment_status' => EmploymentStatus::default()
            ]);
            $contact->employment = [$employment];
            $contact->save();
        }

        //associate the contact with the user
        $user->contact()->associate($contact);
        $user->save();

        event(new ContactRegistered($user->contact));

        //create a reference for the contact
        //and temporary save it to $this->reference class property for further processing
        $this->reference = References::create();
        $this->reference->addEntities($contact);

        //return the user, not the reference
        //it needs to be authorized in the registration controller
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
        $this->register($request->validated());
    }

    public function jsonResponse(): ReferenceMetadata
    {
        return ReferenceMetadata::from($this->reference);
    }
}
