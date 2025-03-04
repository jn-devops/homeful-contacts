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
        // dd($this->getMediaMatrix($contact));
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
        $path = $fileInfo->getFile()->store('uploads', 'digitalocean');
        $url = Storage::disk('digitalocean')->url($path);
        $user->contact->$name = $url;
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
            'voluntarySurrenderFormDocument' => "Voluntary Surrender Form Document",
            'usufructAgreementDocument' => "Usufruct Agreement Document",
            'contractToSellDocument' => "Contract to Sell Document",
            'deedOfRestrictionsDocument' => "Deed of Restrictions Document",
            'disclosureDocument' => "Disclosure Document",
            'borrowerConformityDocument' => "Borrower Conformity Document",
            'statementOfAccountDocument' => "Statement of Account Document",
            'invoiceDocument' => "Invoice Document",
            'receiptDocument' => "Receipt Document",
            'deedOfSaleDocument' => "Deed of Sale Document",
            'governmentId1Image' => "Government ID 1 Image",
            'governmentId2Image' => "Government ID 2 Image",
            'certificateOfEmploymentDocument' => "Certificate of Employment Document",
            'oneMonthLatestPayslipDocument' => "One-Month Latest Payslip Document",
            'esavDocument' => "eSAV Document",
            'birthCertificateDocument' => "Birth Certificate Document",
            'photoImage' => "Photo Image",
            'proofOfBillingAddressDocument' => "Proof of Billing Address Document",
            'letterOfConsentEmployerDocument' => "Letter of Consent (Employer) Document",
            'threeMonthsCertifiedPayslipsDocument' => "Three-Months Certified Payslips Document",
            'employmentContractDocument' => "Employment Contract Document",
            'ofwEmploymentCertificateDocument' => "OFW Employment Certificate Document",
            'passportWithVisaDocument' => "Passport with Visa Document",
            'workingPermitDocument' => "Working Permit Document",
            'notarizedSpaDocument' => "Notarized SPA Document",
            'authorizedRepInfoSheetDocument' => "Authorized Representative Info Sheet Document",
            'validIdAifImage' => "Valid ID (AIF) Image",
            'workingPermitCardDocument' => "Working Permit Card Document",
            'itrBir1701Document' => "ITR (BIR Form 1701) Document",
            'auditedFinancialStatementDocument' => "Audited Financial Statement Document",
            'officialReceiptTaxPaymentDocument' => "Official Receipt (Tax Payment) Document",
            'businessMayorsPermitDocument' => "Business Mayor’s Permit Document",
            'dtiBusinessRegistrationDocument' => "DTI Business Registration Document",
            'sketchOfBusinessLocationDocument' => "Sketch of Business Location Document",
            'letterOfConsentCreditBackgroundInvestigationDocument' => "Letter of Consent (Credit Background Investigation) Document",
            'marriageCertificateDocument' => "Marriage Certificate Document",
            'governmentIdOfSpouseImage' => "Government ID of Spouse Image",
            'courtDecisionAnnulmentDocument' => "Court Decision (Annulment) Document",
            'marriageContractDocument' => "Marriage Contract Document",
            'courtDecisionSeparationDocument' => "Court Decision (Separation) Document",
            'deathCertificateDocument' => "Death Certificate Document",
        ];
        foreach($list_images as $key_matrix => $val_matrix){
            $matrixItem = $contact->$key_matrix ?? null;
    
            $matrices[$key_matrix]['type'] = optional($matrixItem)->mime_type ?? 'unknown';
            $matrices[$key_matrix]['url'] = optional($matrixItem)->getUrl() ?? null;
            $matrices[$key_matrix]['code'] = $key_matrix;
            $matrices[$key_matrix]['name'] = $val_matrix;
        }

        return $matrices;
    }
    
}
