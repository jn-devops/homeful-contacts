<?php

namespace App\Http\Controllers;

use App\Actions\UpdateLoanProcessingContactData;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\AIFUpdateRequest;
use Inertia\{Inertia, Response};

class AIFController extends Controller
{
    public function edit(Request $request): Response
    {
        return Inertia::render('AIF/EditV2', [
            'aif' => $request->user()->contact?->aif,
            'lazarus_url' => config('homeful-contacts.lazarus_url'),
            'lazarus_token' => config('homeful-contacts.lazarus_api_token'),
        ]);
    }

    public function update(AIFUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->contact->update(['aif' => $request->validated()]);
        $user->contact->save();
        $user->save();

        UpdateLoanProcessingContactData::updateContact($user->contact->id);

        return redirect()->back();
    }
}
