<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;

class CompanyProfileController extends Controller
{
    /**
     * Display the single company profile.
     */
    public function show()
    {
        $companyProfile = CompanyProfile::first() ?? new CompanyProfile();
        return view('admin.company-profile.show', compact('companyProfile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompanyProfile $id)
    {
        $companyProfile = CompanyProfile::first() ?? new CompanyProfile();

        return view('admin.company-profile.edit', compact('companyProfile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompanyProfile $id)
    {
        $validated = $request->validate([
            'about'    => 'required|string',
            'address'         => 'nullable|string',
            'whatsapp'           => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            'vision_mission'     => 'nullable|string',
            'instagram'     => 'nullable|string',
        ]);

        // Update existing or create the first record
        CompanyProfile::updateOrCreate(
            ['id' => 1],           // force only 1 record (optional safety)
            $validated
        );

        return redirect()->route('admin.company-profile.show')
            ->with('success', 'Company Profile berhasil diperbarui');
    }
}
