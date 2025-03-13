<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class UnqualifiedUser extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new r   esource.
     */
    public function create(Request $request)
    {
        return Inertia::render('Unqualified', [
            'name' => $request->get('name') ?? '',
            'mobile' => $request->get('mobile') ?? '',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'mobile' => 'required|string',
            'message' => 'nullable|string',
        ]);

        // TODO|Xian TODO|Gecka: Send email to env('STOREFRONT_ADMIN_EMAIL', 'jndevops@joy-nostalg.com') 

        return redirect()->back()->with('flash', [
            'success' => true,
            'message' => 'Email sent successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
