<?php

namespace App\Actions;

use App\Notifications\UnqualifiedUserNotification;
use Illuminate\Support\Facades\Notification;
use Lorisleiva\Actions\Concerns\AsAction;

class NotifySupportReUnqualifiedUser
{
    use AsAction;

    protected function notify(array $validated): void
    {
        Notification::route('mail', config('homeful-contacts.support.email'))
            ->notify(new UnqualifiedUserNotification($validated));
    }

    public function handle(array $attribs): void
    {
        $this->notify(validator($attribs, $this->rules())->validate());
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'mobile' => ['required', 'string'],
            'message' => ['nullable', 'string'],
        ];
    }

    public function asJob(string $name, string $email, string $mobile): void
    {
        $this->handle($name, $email, $mobile);
    }
}
