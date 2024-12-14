<?php

namespace App\Http\Controllers;

use Illuminate\Http\{RedirectResponse, Request};
use App\Http\Requests\MediaUpdateRequest;
use Illuminate\Support\Facades\Redirect;
use Inertia\{Inertia, Response};

class MediaController extends Controller
{
    public function edit(Request $request): Response
    {
        return Inertia::render('Media/Edit', [
            'contact' => $request->user()->contact
        ]);
    }


    public function store(Request $request)
    {
        $user = $request->user();
        $user->contact->addMediaFromRequest('file')
            ->usingName('idImage')
            ->toMediaCollection('id-images')
            ->save();

        return Redirect::route('media.edit');
    }

//    public function update(MediaUpdateRequest $request): RedirectResponse
//    {
//        $user = $request->user();
//        $user->contact->addMediaFromRequest('file')->usingName('idImage')->toMediaCollection('id-images')->save();
////        $user->contact->update(['order' => $request->validated()]);
////        $user->contact->save();
////        $user->save();
//
//        return Redirect::route('media.edit');
//    }
}
