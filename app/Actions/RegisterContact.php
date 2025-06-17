<?php

namespace App\Actions;

use Carbon\Carbon;
use Homeful\Contacts\Enums\{CivilStatus, CoBorrowerType, Employment, EmploymentStatus, Nationality, Sex, Suffix};
use Illuminate\Support\Facades\Date;
use Illuminate\Validation\{Rule, Rules, ValidationException};
use Homeful\Contacts\Classes\ReferenceMetadata;
use Propaganistas\LaravelPhone\Rules\Phone;
use Homeful\References\Facades\References;
use App\Models\{Contact, Reference, User};
use Lorisleiva\Actions\Concerns\AsAction;
use App\Rules\{FullNameRequired, Income};
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Support\Facades\Hash;
use Homeful\Contacts\Classes\Dummy;
use App\Events\ContactRegistered;
use Illuminate\Support\Arr;
use App\Data\UserData;
use App\Helper\WelcomeSMS;
use App\Notifications\RegistrationWelcomeNotificationForSellerApp;

class RegisterContact
{
    use AsAction;

    protected User $user;

    public Reference $reference;

    protected function register(array $validated): User
    {
        context(Arr::only($validated, 'password'));

        //hash the validated password, a Laravel best practice
        //as per documentation the hashed password stored in the database will be compared with the password value
        // passed to the Auth::attempt method via the array
        $validated['password'] = Hash::make($validated['password']);

        //pull the gmi from the list of attributes for processing
        //it is not needed in the persisting of the contact
        //but will be used later in the employment
        $gmi = (float) Arr::pull($validated, 'monthly_gross_income');
        $cobo_gmi = (float) Arr::pull($validated, 'cobo_monthly_gross_income');
        $cobo_date_of_birth = Carbon::parse(Arr::pull($validated, 'cobo_date_of_birth'))->format('Y-m-d');

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

        if ($cobo_gmi > 0.0 && $cobo_date_of_birth !=null) {
            $co_borrowers[] = [
                'type' => CoBorrowerType::default(),
                'first_name' => 'Coborrower 1',
                'last_name' => 'Coborrower 1',
                'name_suffix'=> Suffix::default(),
                'civil_status' => CivilStatus::default(),
                'sex'=> Sex::MALE,
                'nationality'=> Nationality::default(),
                'date_of_birth' => $cobo_date_of_birth,
                "nationality" => "Filipino",
                "civil_status" => "Single", 
                'employment'=>[
                    [
                        'type' => Employment::default(),
                        "rank" => null,
                        'monthly_gross_income' => $cobo_gmi,
                        'employment_status' => EmploymentStatus::default(),
                        'id' => [
                            'tin' => Dummy::TIN,
                            "sss" => null,
                            "gsis" => null,
                            "pagibig" => null
                        ],
                        "employer" => [
                            "name" => "---",
                            "email" => null,
                            "address" => [
                                "type" => "Work",
                                "region" => null,
                                "country" => "PH",
                                "address1" => null,
                                "locality" => null,
                                "ownership" => null,
                                "sublocality" => null,
                                "administrative_area" => null
                            ],
                            "industry" => null,
                            "contact_no" => null,
                            "nationality" => null,
                            "year_established" => null,
                            "total_number_of_employees" => null
                        ],
                        "employment_type" => null,
                        "current_position" => null,
                        "years_in_service" => null,
                    ]
                ]
            ];
            $contact->update(['co_borrowers' => $co_borrowers]);
        }

        //associate the contact with the user
        $user->contact()->associate($contact);
        $user->save();

        //create a reference for the contact
        //and temporary save it to $this->reference class property for further processing
        $reference = References::withOwner($user)->create();
        $reference->addEntities($contact);

        $user->notify(new RegistrationWelcomeNotificationForSellerApp($reference, context('password')));
        WelcomeSMS::send($reference, context('password'));

        //set the reference context
        //just like a session
        context(['reference' => $reference]);

        //return the user, not the reference
        //it needs to be authorized in the registration controller
        return $user;
    }

    /**
     * @throws ValidationException
     */
    public function handle(array $attribs): ?User
    {
        $validated = validator($attribs, $this->rules())->validated();

        return $this->register($validated);
    }

    public function rules(): array
    {
        return [
            'first_name' => ['nullable', 'string', 'max:50'],
            'middle_name' => ['nullable', 'string', 'max:50'],
            'last_name' => ['nullable', 'string', 'max:50'],
            'name' => ['string', 'max:255', new FullNameRequired],
            'name_suffix' => ['nullable', Rule::enum(Suffix::class)],
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'mobile' => ['required', (new Phone)->type('mobile')->country('PH'), 'unique:'.User::class],
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
            'order' => ['nullable', 'array'],
            'cobo_monthly_gross_income' => ['nullable', 'string'],
            'cobo_date_of_birth' => ['nullable', 'date'],

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

    public function getReference(): ?Reference
    {
        return context('reference');
    }
}
