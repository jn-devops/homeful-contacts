<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmploymentUpdateRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;

class EmploymentController extends Controller
{
    public function edit(Request $request): Response
    {
        return Inertia::render('Personal/Edit', [
            'contact' => $request->user()->contact
        ]);
    }

    public function update(EmploymentUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $records = $user->contact?->employment?->toCollection()?->mapWithKeys(function ($item) {
            return [$item->type->value => $item];
        })->toArray() ?? [];

        $data = $request->validated();
        $type = $data['type'];
        $records[$type] = $data;

        $employment_records = [];
        foreach($records as $type => $employment_record){
            $employment_records [] = $employment_record;
        }

        $user->contact->update(['employment' => $employment_records]);
        $user->contact->save();
        $user->save();

        return Redirect::route('personal.edit');
    }
}
