<?php

namespace App\Http\Controllers;

use FrittenKeeZ\Vouchers\Models\VoucherEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RedirectControllers extends Controller
{
    public function redirect_to_consult(Request $request){
        $homeful_id = VoucherEntity::where('entity_id', $request->user()->contact_id)->first()->voucher->code ?? '';
        return Inertia::location(config('contract.contract_url').'consult/create?contact_reference_code='.$homeful_id);
    }
}
