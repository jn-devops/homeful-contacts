<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class CheckAge
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $birthdate = $request->get('date_of_birth') ?? '';
        $age = Carbon::parse($birthdate)->age;
        if($age < 21  || $age > 60) {
            // return redirect()->route('unqualified-user.create', ['mobile' => $request->get('mobile'), 'name' => $request->get('name')]);
            return Inertia::location(route('unqualified-user.create', ['mobile' => $request->get('mobile'), 'name' => $request->get('name')]));
        }
        return $next($request);
    }
}
