<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use App\Models\Hospital;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class HospitalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $hospital = Hospital::with([
            'country',
            'state',
            'city',
        ])->first();

        $countries = Country::orderBy('name')->get();
        $states = State::orderBy('name')->get();
        $cities = City::orderBy('name')->get();

        return view('hospital.index', [
            'hospital' => $hospital,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $hospital = Hospital::with([
            'country',
            'state',
            'city',
        ])->first();

        $countries = Country::orderBy('name')->get(); dd($countries);
        $states = State::orderBy('name')->get();
        $cities = City::orderBy('name')->get();

        return view('hospital.index', [
            'hospital' => $hospital,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function store(Request $request, string $id)
    {
        $hospital = Hospital::first();
       
        $validator = Validator::make($request->all(), [
            'hospital_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'tax_registration_number' => 'nullable|string|max:50',

            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'company_seal' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]); 
        if ($validator->fails()) {
            return redirect()
                ->route('hospital')
                ->withErrors($validator)
                ->withInput();    

        }else{
            $validated = $validator->validated(); 
        
            /*
            |--------------------------------------------------------------------------
            | Logo
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('logo')) {

                // Delete old logo
                if ($hospital->logo) {
                    Storage::disk('public')->delete($hospital->logo);
                }

                // Save new logo
                $validated['logo'] = $request->file('logo')
                    ->store('hospital', 'public');
            }

            /*
            |--------------------------------------------------------------------------
            | Company Seal
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('company_seal')) {

                // Delete old seal
                if ($hospital->company_seal) {
                    Storage::disk('public')->delete($hospital->company_seal);
                }

                // Save new seal
                $validated['company_seal'] = $request->file('company_seal')
                    ->store('hospital', 'public');
            }

            $hospital->update($validated);

            return redirect()
                ->route('hospital')
                ->with('success', 'Hospital details updated successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
