<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Files\FileController;


class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = Setting::first();
        return view('Dashboard.settings.index', compact('settings'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting)
    {
         $request->validate([
            'phone' => 'required|numeric',
            'email' => 'required|email|max:255',
            'logo'=>'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
           
        ]);
       $setting->logo = FileController::updateFile($request->file('logo'), $setting->logo,'uploads/settings');
        $setting->save();
        $setting->update([
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
