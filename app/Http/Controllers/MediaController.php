<?php

namespace App\Http\Controllers;

use Illuminate\Http\{RedirectResponse, Request};
use App\Http\Requests\MediaUpdateRequest;
use Homeful\Contacts\Models\Customer;
use RahulHaque\Filepond\Facades\Filepond;
use Inertia\{Inertia, Response};
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function edit(Request $request): Response
    {
        $contact = $request->user()->contact;
        return Inertia::render('Media/EditV2', [
            'contact' => $contact,
            'matrices' => $this->getMediaMatrix($contact),
        ]);
    }

    public function update(MediaUpdateRequest $request): RedirectResponse
    {
        $name = $request->validated('name');
        // $collection_name = Arr::get($this->getMediaMatrix(), $name);
        $fileInfo = Filepond::field($request->get('file'));
        $user = $request->user();
        $path = $fileInfo->getFile()->store('uploads', 'public');
        $url = Storage::url($path);

        $user->contact->$name = asset($url);
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

    protected function getMediaMatrix($contact): array
    {
        $matrices = [];
        $list_images = [
            'idImage' => "ID Image",
            'selfieImage' => "Selfie Image",
            'payslipImage' => "Payslip Image",
        ];
        foreach($list_images as $key_matrix => $val_matrix){
            $matrices[$key_matrix]['url'] = $contact->$key_matrix->preview_url ?? '';
            $matrices[$key_matrix]['code'] = $key_matrix;
            $matrices[$key_matrix]['name'] = $val_matrix;
        }

        return $matrices;
    }
    
}
