<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Invoice') }}
            </h2>

            <button
                onclick="window.print()"
                class="bg-nblue-600 text-white px-4 py-2 rounded-md hover:bg-nblue-700">
                Print
            </button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-lg p-8">

                <!-- Hospital Header -->
                <div class="flex justify-between items-start border-b pb-6">

                    <div>
                        @if($hospital?->logo)
                            <img
                                src="{{ asset('storage/' . $hospital->logo) }}"
                                class="h-20 mb-3"
                                alt="Hospital Logo">
                        @endif

                        <h1 class="text-2xl font-bold text-gray-800">
                            {{ $hospital?->name }}
                        </h1>

                        <p class="text-sm text-gray-600">
                            {{ $hospital?->address }}
                        </p>

                        <p class="text-sm text-gray-600">
                            {{ $hospital?->city?->name }},
                            {{ $hospital?->state?->name }},
                            {{ $hospital?->country?->name }}
                        </p>

                        <p class="text-sm text-gray-600">
                            Tel: {{ $hospital?->telephone_number }}
                        </p>

                        <p class="text-sm text-gray-600">
                            Email: {{ $hospital?->email }}
                        </p>
                    </div>

                    <div class="text-right">
                        <h2 class="text-3xl font-bold text-gray-800">
                            TAX INVOICE
                        </h2>

                        <p class="mt-2">
                            <strong>Invoice No:</strong>
                            {{ $invoice->invoice_number }}
                        </p>

                        <p>
                            <strong>Invoice Date:</strong>
                            {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-m-Y') }}
                        </p>

                        @if($invoice->due_date)
                            <p>
                                <strong>Due Date:</strong>
                                {{ \Carbon\Carbon::parse($invoice->due_date)->format('d-m-Y') }}
                            </p>
                        @endif

                        <p>
                            <strong>Status:</strong>
                            {{ $invoice->payment_status }}
                        </p>
                    </div>

                </div>

                <!-- Client -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">
                            Bill To
                        </h3>

                        <p class="font-medium">
                            {{ $invoice->client?->name }}
                        </p>

                        <p class="text-sm text-gray-600">
                            {{ $invoice->client?->address }}
                        </p>

                        <p class="text-sm text-gray-600">
                            {{ $invoice->client?->city?->name }},
                            {{ $invoice->client?->state?->name }},
                            {{ $invoice->client?->country?->name }}
                        </p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">
                            Service
                        </h3>

                        <p class="font-medium">
                            {{ $invoice->service?->name }}
                        </p>

                        <p class="text-sm text-gray-600">
                            {{ $invoice->service?->description }}
                        </p>
                    </div>

                </div>

                <!-- Workers -->
                <div class="mt-8">

                    <h3 class="font-semibold text-gray-700 mb-3">
                        Workers
                    </h3>

                    <table class="w-full border-collapse border">

                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-3 text-left w-16">
                                    #
                                </th>

                                <th class="border p-3 text-left">
                                    Worker Name
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($invoice->items as $index => $item)
                                <tr>
                                    <td class="border p-3">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $item->worker_name }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div>

                <!-- Amounts -->
                <div class="mt-6 flex justify-end">

                    <table class="w-full md:w-1/2">

                        <tr>
                            <td class="py-2 text-right pr-6">
                                Quantity
                            </td>
                            <td class="py-2 text-right">
                                {{ $invoice->quantity }}
                            </td>
                        </tr>

                        <tr>
                            <td class="py-2 text-right pr-6">
                                Unit Price
                            </td>
                            <td class="py-2 text-right">
                                AED {{ number_format($invoice->unit_price, 2) }}
                            </td>
                        </tr>

                        <tr>
                            <td class="py-2 text-right pr-6">
                                Net Amount
                            </td>
                            <td class="py-2 text-right">
                                AED {{ number_format($invoice->net_amount, 2) }}
                            </td>
                        </tr>

                        <tr>
                            <td class="py-2 text-right pr-6">
                                VAT (5%)
                            </td>
                            <td class="py-2 text-right">
                                AED {{ number_format($invoice->vat, 2) }}
                            </td>
                        </tr>

                        <tr>
                            <td class="py-2 text-right pr-6">
                                Discount
                            </td>
                            <td class="py-2 text-right">
                                AED {{ number_format($invoice->discount, 2) }}
                            </td>
                        </tr>

                        <tr class="border-t font-bold text-lg">
                            <td class="pt-3 text-right pr-6">
                                Total
                            </td>
                            <td class="pt-3 text-right">
                                AED {{ number_format($invoice->total, 2) }}
                            </td>
                        </tr>

                    </table>

                </div>

                <!-- Footer -->
                <div class="mt-10 pt-6 border-t text-sm text-gray-600">

                    <div class="flex justify-between">

                        <div>
                            <p>
                                <strong>TRN:</strong>
                                {{ $hospital?->tax_registration_number }}
                            </p>
                        </div>

                        <div class="text-center">
                            @if($hospital?->company_seal)
                                <img
                                    src="{{ asset('storage/' . $hospital->company_seal) }}"
                                    class="h-20 mx-auto"
                                    alt="Company Seal">
                            @endif

                            <p class="mt-2">
                                Authorized Signature
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .bg-white,
            .bg-white * {
                visibility: visible;
            }

            .bg-white {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none !important;
            }

            button,
            header {
                display: none !important;
            }
        }
    </style>

</x-app-layout>