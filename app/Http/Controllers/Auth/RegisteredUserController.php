<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\{JsonResponse, RedirectResponse, Request, Response as HttpResponse};
use Illuminate\Validation\ValidationException;
use Homeful\References\Models\Reference;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Actions\RegisterContact;
use App\Events\ContactRegistered;
use App\Models\Contact;
use App\Models\User;
use App\Notifications\RegistrationWelcomeNotificationForSellerApp;
use Carbon\Carbon;
use Homeful\Contacts\Classes\Dummy;
use Homeful\Contacts\Enums\CivilStatus;
use Homeful\Contacts\Enums\CoBorrowerType;
use Homeful\Contacts\Enums\Employment;
use Homeful\Contacts\Enums\EmploymentStatus;
use Homeful\Contacts\Enums\Nationality;
use Homeful\Contacts\Enums\Sex;
use Homeful\Contacts\Enums\Suffix;
use Homeful\References\Facades\References;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Inertia\{Inertia, Response};
use Illuminate\Support\Str;
use Spatie\Url\Url;

class RegisteredUserController extends Controller
{
    const REGISTER_VIEW = 'Auth/Register';

    public function create(Request $request): Response
    {
        $this->setCallbackToSession($request);

        $callback = $request->get('callback', config('homeful-contacts.callback'));
        $showExtra = config('homeful-contacts.show_gmi') || $request->get('showExtra');
        $hidePassword = config('homeful-contacts.hide_password') || $request->get('hidePassword');
        $autoPassword = $hidePassword ? config('homeful-contacts.default_password') : '';

        return Inertia::render(self::REGISTER_VIEW, [
            'callback' => $callback,
            'showExtra' => $showExtra,
            'autoPassword' => $autoPassword,
            'name' => $request->get('name',''),
            'email' => $request->get('email',''),
            'mobile' => $request->get('mobile',''),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): \Illuminate\Foundation\Application|\Illuminate\Routing\Redirector|\Symfony\Component\HttpFoundation\Response|RedirectResponse
    {
        $user = app(RegisterContact::class)->run($request->all());

        event(new Registered($user));

        Auth::login($user);

        return session('callback')
            ? Inertia::location($this->getCallbackWithQueryParamsFromSession(context('reference')))
            : redirect(route('dashboard', absolute: false));
    }

    protected function setCallbackToSession(Request $request): self
    {
        session($request->only('callback'));

        return $this;
    }

    protected function getCallbackWithQueryParamsFromSession(Reference $reference): string
    {
        $url = Url::fromString(session('callback'));

        return $url->withQueryParameters(['contact_reference_code' => $reference->code]);
    }

    public function createContactForSellerApp(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'last_name' => 'required|string',
                'first_name' => 'required|string',
                'date_of_birth' => 'required|date',
                'email' => 'required|email',
                'password' => 'nullable|string',
                'mobile' => 'required|string',
                'gross_monthly_income' => 'required|numeric',
                'cobo_gross_monthly_income' => 'nullable|numeric',
                'cobo_date_of_birth' => 'nullable|date',
            ]);

            
            $gmi = (float) Arr::pull($validated, 'gross_monthly_income');
            $cobo_gmi = (float) Arr::pull($validated, 'cobo_gross_monthly_income');
            $cobo_date_of_birth = Carbon::parse(Arr::pull($validated, 'cobo_date_of_birth'))->format('Y-m-d');
            $password = $validated['password'] ?? Str::uuid();
            $validated['password'] = Hash::make($password);

            $contact = Contact::where('last_name', $validated['last_name'])
                ->where('first_name', $validated['first_name'])
                ->where('date_of_birth', $validated['date_of_birth'])
                ->first();

            if ($contact) {
                return response()->json([
                    'success' => true,
                    'message' => 'Contact already exists.',
                    'data' => $contact,
                ], HttpResponse::HTTP_OK);
            }

            $user = app(User::class)->create([
                'name' => "{$validated['first_name']} {$validated['last_name']}",
                'email' => $validated['email'],
                'mobile' => $validated['mobile'],
                'password' => $validated['password'],
            ]);

            $contact = Contact::create($validated);
            $user->contact()->associate($contact);
            $user->save();

            $reference = References::withOwner($user)->create();
            $reference->addEntities($contact);

            $order = $contact->order ?? [];
            $order['homeful_id'] = $reference->code;
            $contact->order = $order;
            $contact->save();

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

            // Created own Welcome Email
            // event(new ContactRegistered($reference));
            $user->notify(new RegistrationWelcomeNotificationForSellerApp($reference, $password));

            return response()->json([
                'success' => true,
                'message' => 'Contact created successfully.',
                'data' => $contact,
            ], HttpResponse::HTTP_CREATED);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            Log::error('CreateContactForSellerApp error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' =>  $e->getMessage(),
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
