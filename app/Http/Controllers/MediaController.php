<?php

namespace App\Http\Controllers;

use Illuminate\Http\{RedirectResponse, Request};
use App\Http\Requests\MediaUpdateRequest;
use RahulHaque\Filepond\Facades\Filepond;
use Inertia\{Inertia, Response};
use Illuminate\Support\Arr;

class MediaController extends Controller
{
    public function edit(Request $request): Response
    {
        return Inertia::render('Media/EditV2', [
            'contact' => $request->user()->contact
                ->append(array_keys($this->getMediaMatrix()))
        ]);
    }

    public function update(MediaUpdateRequest $request): RedirectResponse
    {
        $name = $request->validated('name');
        $collection_name = Arr::get($this->getMediaMatrix(), $name);
        $fileInfo = Filepond::field($request->get('file'));
        $user = $request->user();

        $user->contact->addMedia($fileInfo->getFile()->getPathname())
            ->usingName($name)
            ->toMediaCollection($collection_name)
            ->save();
        $user->contact->save();
        $fileInfo->delete();

        return redirect()->back()->with('name', $name);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();
        $name = $request->get('name');
        $media = $user->contact->getAttribute($name);
        $user->contact->deleteMedia($media);

        return redirect()->back();
    }

    protected function getMediaMatrix(): array
    {
        return [
            'idImage' => 'id-images',
            'selfieImage' => 'selfie-images',
            'payslipImage' => 'payslip-images',
            'signatureImage' => 'signature-image' //TODO: change to 'signature-images in jn-devops/contacts
        ];
    }
}
