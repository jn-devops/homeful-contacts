<?php

namespace App\Http\Controllers;

use Illuminate\Http\{RedirectResponse, Request};
use App\Http\Requests\SpouseUpdateRequest;
use Illuminate\Support\Facades\Redirect;
use Inertia\{Inertia, Response};

class SpouseController extends Controller
{
    public function edit(Request $request): Response
    {
        return Inertia::render('Spouse/Edit', [
            'spouse' => $request->user()->contact?->spouse
        ]);
    }

    public function update(SpouseUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->contact->update(['spouse' => $request->validated()]);
        $user->contact->save();
        $user->save();

        return Redirect::route('spouse.edit');
    }
}
