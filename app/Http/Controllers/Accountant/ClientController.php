<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Client;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::with([
            'country',
            'state',
            'city',
        ])
        ->latest()
        ->paginate(10);

        return view('client.index', compact('clients'));
    }

    public function create()
    {
        $countries = Country::orderBy('name')->get();
        $states = State::orderBy('name')->get();
        $cities = City::orderBy('name')->get();

        return view('client.create', [
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'address' => ['nullable', 'string'],

            'country_id' => [
                'required',
                'exists:country,id',
            ],

            'state_id' => [
                'required',
                'exists:state,id',
            ],

            'city_id' => [
                'required',
                'exists:city,id',
            ],
        ]);

        Client::create($validated);

        return redirect()
            ->route('clients')
            ->with('success', 'Client created successfully.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $client = Client::with([
            'country',
            'state',
            'city',
        ])->first();

        $countries = Country::orderBy('name')->get();
        $states = State::orderBy('name')->get();
        $cities = City::orderBy('name')->get();

        return view('client.edit', [
            'client' => $client,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $client = Client::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'address' => ['nullable', 'string'],

            'country_id' => [
                'required',
                'exists:country,id',
            ],

            'state_id' => [
                'required',
                'exists:state,id',
            ],

            'city_id' => [
                'required',
                'exists:city,id',
            ],
        ]);

        $client->update($validated);

        return redirect()
            ->route('clients')
            ->with('success', 'Client updated successfully.');
    }

    public function delete(string $id)
    {
        $client = Client::findOrFail($id);

        $client->delete();

        return redirect()
        ->route('clients')
        ->with('success', 'Client deleted successfully.');
    }
}