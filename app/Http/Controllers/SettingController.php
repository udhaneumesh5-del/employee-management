<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Role-based settings access
        $isAdmin = $user->isAdmin();
        $isHR = $user->isHR();

        return view('settings.index', compact('isAdmin', 'isHR'));
    }

   
    // General Settings (Admin & HR)
   
    public function general()
    {
        $settings = DB::table('settings')->first();
        return view('settings.general', compact('settings'));
    }

    // Company Profile (Admin & HR)
   
    public function company()
    {
        $company = DB::table('company_profiles')->first();
        return view('settings.company', compact('company'));
    }

    // Update General Settings (Admin only)
   
    public function updateGeneral(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Only Admin can update settings.');
        }

        $request->validate([
            'company_name' => 'required|string|max:255',
            'timezone' => 'required|string|max:50',
        ]);

        DB::table('settings')->updateOrInsert(
            ['id' => 1],
            [
                'company_name' => $request->company_name,
                'timezone' => $request->timezone,
                'updated_at' => now()
            ]
        );

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully!');
    }

    // Update Company Profile (Admin only)
   
    public function updateCompany(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Only Admin can update company profile.');
        }

        $request->validate([
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        DB::table('company_profiles')->updateOrInsert(
            ['id' => 1],
            [
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'updated_at' => now()
            ]
        );

        return redirect()->route('settings.index')
            ->with('success', 'Company profile updated successfully!');
    }
}