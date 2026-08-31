<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::latest()->paginate(10);

        return view('service.index', compact('services'));
    }

    public function create()
    {
        return view('service.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'standard_price' => ['required', 'numeric', 'min:0'],
            'auto_invoice_number' => ['nullable', 'boolean'],
        ]);

        $validated['auto_invoice_number'] =
            $request->boolean('auto_invoice_number');

        Service::create($validated);

        return redirect()
            ->route('services')
            ->with('success', 'Service created successfully.');
    }

    public function edit(string $id)
    {
        $service = Service::findOrFail($id);

        return view('service.edit', compact('service'));
    }

    public function update(Request $request, string $id)
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'standard_price' => ['required', 'numeric', 'min:0'],
            'auto_invoice_number' => ['nullable', 'boolean'],
        ]);

        $validated['auto_invoice_number'] =
            $request->boolean('auto_invoice_number');

        $service->update($validated);

        return redirect()
            ->route('services')
            ->with('success', 'Service updated successfully.');
    }

    public function delete(string $id)
    {
        $service = Service::findOrFail($id);

        $service->delete();

        return redirect()
            ->route('services')
            ->with('success', 'Service deleted successfully.');
    }
}