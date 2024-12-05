<?php

namespace App\Http\Controllers;

use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\AIFUpdateRequest;
use Inertia\{Inertia, Response};

class AIFController extends Controller
{
    public function edit(Request $request): Response
    {
        return Inertia::render('AIF/Edit', [
            'aif' => $request->user()->contact?->order
        ]);
    }

    public function update(AIFUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->contact->update(['order' => $request->validated()]);
        $user->contact->save();
        $user->save();

        return Redirect::route('aif.edit');
    }
}
