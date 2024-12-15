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
            'contact' => $request->user()->contact->append([
                'idImage',
                'selfieImage',
                'payslipImage',
                'voluntarySurrenderFormDocument',
                'usufructAgreementDocument',
                'contractToSellDocument'
            ])
        ]);
    }

    public function store(MediaUpdateRequest $request)
    {
        $user = $request->user();
        $name = $request->validated('name');

        $collection_name = match ($name) {
            'idImage' => 'id-images',
            'selfieImage' => 'selfie-images',
            'payslipImage' => 'payslip-images',
            'voluntarySurrenderFormDocument' => 'voluntary_surrender_form-documents',
            'usufructAgreementDocument' => 'usufruct_agreement-documents',
            'contractToSellDocument' => 'contract_to_sell-documents'
        };

        $user->contact->addMediaFromRequest('file')
            ->usingName($name)
            ->toMediaCollection($collection_name)
            ->save();
        $user->contact->save();

        return back()->with('file_name', $name);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();
        $user->refresh();
        $name = $request->get('name');
        $user->contact->refresh();
        $media = $user->contact->getAttribute($name);
        $user->contact->fresh()->deleteMedia($media);

        return Redirect::route('media.edit');
        return back();
    }
}
