<?php

namespace App\Actions;

use Homeful\References\Facades\References;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Validator;
use Homeful\References\Models\Reference;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Homeful\Contacts\Models\Contact;
use Illuminate\Support\{Arr, Str};
use App\Models\User;

class CreateUserContact
{
    use AsAction;

    protected function create(array $attribs): ?Reference
    {
        $user = User::create([
            'name' => $attribs['first_name'] . ' ' . $attribs['last_name'],
            'email' => $attribs['email'],
            'mobile' => $attribs['mobile'],
            'password' => Str::password(),
        ]);

        $reference = null;

        $validator = Validator::make($attribs, [
            'middle_name' => ['required', 'string'],
            'civil_status' => ['required', 'string'],
            'sex' => ['required', 'string'],
            'nationality' => ['required', 'string'],
            'date_of_birth' => ['required', 'string'],
        ]);

        if ($validator->passes()) {
            $attribs = array_merge($validator->validated(), [
                'email' => $attribs['email'],
                'mobile' => $attribs['mobile'],
                'first_name' => $attribs['first_name'],
                'last_name' => $attribs['last_name']
            ]);
            $contact = Contact::create($attribs);
            $user->contact()->associate($contact);
            $user->save();
            $reference = References::create();
            if ($reference instanceof Reference) {
                $reference->addEntities($contact);
            }
        }

        event(new Registered($user));

        return $reference;
    }

    public function handle(array $attribs)
    {
        return $this->create(Validator::validate($attribs, $this->rules()));
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'mobile' => ['required', 'string'],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],

            'middle_name' => ['nullable', 'string'],
            'civil_status' => ['nullable', 'string'],
            'sex' => ['nullable', 'string'],
            'nationality' => ['nullable', 'string'],
            'date_of_birth' => ['nullable', 'string'],
        ];
    }
}
