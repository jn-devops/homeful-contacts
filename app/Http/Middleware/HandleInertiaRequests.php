<?php

namespace App\Http\Middleware;

use Homeful\Contacts\Enums\{AddressType, CivilStatus, Employment,
    EmploymentStatus, EmploymentType, Industry,
    Nationality, Ownership, Sex};
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        if ($user)
            if ($user->contact)
                $user->load('contact');

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
            ],
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'warning' => fn () => $request->session()->get('warning'),
                'data' => fn () => $request->session()->get('data'),
                'event' => fn () => $request->session()->get('event'),
            ],
            'enums' => [
                'address_types' => AddressType::toArray(),
                'employment_indices' => Employment::toArray(),
                'civil_statuses' => CivilStatus::toArray(),
                'sexes' => Sex::toArray(),
                'nationalities' => Nationality::toArray(),
                'ownerships' => Ownership::toArray(),
                'employment_types' => EmploymentType::toArray(),
                'employment_statuses' => EmploymentStatus::toArray(),
                'industries' => Industry::toArray(),
            ],
            'csrf_token' => csrf_token(),
        ];
    }
}
