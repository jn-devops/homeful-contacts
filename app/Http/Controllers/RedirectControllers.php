<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RedirectControllers extends Controller
{
    public function redirect_to_consult(){
        return Inertia::location(config('contract.contract_url').'consult/create?contact_reference_code=');
    }
}
