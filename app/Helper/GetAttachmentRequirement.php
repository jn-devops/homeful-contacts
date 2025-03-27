<?php

namespace App\Helper;

use Homeful\Contacts\Models\Contact;
use Homeful\Contacts\Models\Customer;
use Illuminate\Support\Facades\Http;

class GetAttachmentRequirement
{
    public static function getAttachmentByID($id){
        $contact = Contact::find($id);
        return self::getMediaMatrix($contact);
    }
    
    private static function getContactMediaMatrix(){
        return [
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
            'oneMonthLatestPayslipDocument' => "One Month Latest Payslip Document",
            'esavDocument' => "ESAV Document",
            'birthCertificateDocument' => "Birth Certificate Document",
            'photoImage' => "Photo Image",
            'proofOfBillingAddressDocument' => "Proof of Billing Address Document",
            'letterOfConsentEmployerDocument' => "Letter of Consent Employer Document",
            'threeMonthsCertifiedPayslipsDocument' => "Three Months Certified Payslips Document",
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
            'marriageCertificateDocument' => "Marriage Certificate/Contract Document",
            'governmentIdOfSpouseImage' => "Government ID of Spouse Image",
            'courtDecisionAnnulmentDocument' => "Court Decision (Annulment) Document",
            // 'marriageContractDocument' => "Marriage Contract Document",
            'courtDecisionSeparationDocument' => "Court Decision (Separation) Document",
            'deathCertificateDocument' => "Death Certificate Document",
        ];
    }

    private static function getMediaMatrix($contact): array
    {
        $matrices = [];
        $list_images = self::getFinalListRequirementMatrix($contact);
        $cntr = 0;
        foreach($list_images as $key_matrix => $val_matrix){
            $customer = Customer::find($contact->id)->$key_matrix;
            $matrices[$cntr]['code'] = $key_matrix;
            $matrices[$cntr]['name'] = $val_matrix;
            $matrices[$cntr]['type'] = optional($customer)->mime_type ?? 'unknown';
            $matrices[$cntr]['url'] = optional($customer)->getUrl() ?? null;
            $cntr++;
        }

        return $matrices;
    }

    private static function getFinalListRequirementMatrix($contact){
        $api_matrix = self::getRequirementMatrix($contact);
        // dd(is_array($api_matrix));
        $allRequirements = [];
        foreach($api_matrix as $matrix){
            $allRequirements = array_merge($allRequirements, json_decode($matrix['requirements'], true));
        }
        $uniqueRequirements = array_values(array_unique($allRequirements));
        
        $contact_matrix = self::getContactMediaMatrix();

        $filteredDocuments = array_filter($contact_matrix, function ($value) use ($uniqueRequirements) {
            return in_array($value, $uniqueRequirements);
        });
        return $filteredDocuments;
    }

    private static function getRequirementMatrix($contact){
        $response = Http::post(config('contract.contract_url').'api/requirement-matrix-filtered', [
            'employment_status' => $contact->employment[0]['employment_type'] ?? '',
            'civil_status' => $contact->civil_status ?? '',
        ]);
        if ($response->successful()) {
            return $response->json();
        }else{
            return [];
        }
        
    }
}
