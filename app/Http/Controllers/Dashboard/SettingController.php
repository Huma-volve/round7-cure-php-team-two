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
        return view('dashboard.settings.index', compact('settings'));
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
        //return $request;
        $request->validate([
            'phone' => 'required',
            'email' => 'required|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,webp,jpg,gif,svg|max:2048',
            'rate' => 'required|numeric|min:1|max:30'

        ]);
        $setting->logo = FileController::updateFile($request->file('logo'), $setting->logo, 'uploads/settings');
        $setting->save();
        $setting->update([
            'phone' => $request->phone,
            'email' => $request->email,
            'rate' => $request->rate,
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
