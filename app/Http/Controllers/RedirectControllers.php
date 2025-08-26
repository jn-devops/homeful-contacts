<?php

namespace App\Http\Controllers;

use FrittenKeeZ\Vouchers\Models\VoucherEntity;
use Homeful\References\Models\Reference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use MagicLink\Actions\LoginAction;
use MagicLink\MagicLink;

class RedirectControllers extends Controller
{
    public function redirect_to_consult(Request $request){
        $homeful_id = VoucherEntity::where('entity_id', $request->user()->contact_id)->first()->voucher->code ?? '';
        return Inertia::location(config('contract.contract_url').'consult/create?contact_reference_code='.$homeful_id);
    }

    /**
     * @param string $homeful_id
     * 
     * @return \Inertia\Response
     * 
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * 
     * This function takes a homeful_id as a parameter and redirects to the review/personal page.
     * If the homeful_id does not exist it will return a 404 error.
     * If an exception is thrown it will return a 500 error.
     */
    public function redirect_via_homeful_id($homeful_id){
        try {
            $reference = Reference::where('code', $homeful_id)->first();
            if(!$reference){
                return response()->json([
                    'success' => false,
                    'message' => 'Homeful ID Not Found',
                    'data' => null,
                ], 404);
            }
            $action = new LoginAction($reference->owner);
            $action->response(redirect('/review/personal'));
            return Inertia::location(MagicLink::create($action)->url);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
                'data' => null,
            ], 500);
        }
    }
}
