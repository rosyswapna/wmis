<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Invoices') }}
            </h2>            
        </div>
    </x-slot>

    <div x-data="{ filterOpen: false }" class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">        

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            
            <div class="bg-white shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">
                                Invoices ({{ $invoices->total() }})
                            </h3>

                            <p class="mt-1 text-sm text-gray-600">
                                Manage invoices and print invoice documents.
                            </p>
                        </div> 
                        <div class="text-right">

                            <!-- Filter Button -->
                            <button type="button"
                                    @click="filterOpen = !filterOpen"
                                    class="inline-flex items-center justify-center
                                        w-9 h-9 rounded-md px-4 py-2
                                        text-gray-600 hover:text-gray-900
                                        hover:bg-gray-100">

                                <i class="fas fa-filter"></i>
                            </button>

                            <button type="button"
                                    class="inline-flex items-center justify-center
                                        w-9 h-9 rounded-md px-4 py-2
                                        text-gray-600 hover:text-gray-900
                                        hover:bg-gray-100">

                                <i class="fas fa-file-export"></i>
                            </button> 
                            

                            <x-table-link href="{{ route('invoices.create') }}">
                                {{ __('+ Create New') }}
                            </x-table-link>

                        </div>
                    </div>

                    <!-- Filter Section -->
                    <div x-show="filterOpen"
                        x-transition
                        x-cloak
                        class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-lg">

                        <div class="flex items-end gap-3">

                            <!-- From Date -->
                            <div class="w-48">
                                <label class="block text-sm font-medium text-gray-700">
                                    From Date
                                </label>

                                <input type="date"
                                    id="date_from"
                                    class="mt-1 block w-full h-9 rounded-md border-gray-300">
                            </div>

                            <!-- To Date -->
                            <div class="w-48">
                                <label class="block text-sm font-medium text-gray-700">
                                    To Date
                                </label>

                                <input type="date"
                                    id="date_to"
                                    class="mt-1 block w-full h-9 rounded-md border-gray-300">
                            </div>

                            <!-- Client -->
                            <div class="w-56">
                                <label class="block text-sm font-medium text-gray-700">
                                    Client
                                </label>

                                <select id="client_id"
                                        class="mt-1 block w-full h-9 rounded-md border-gray-300">
                                    <option value="">All Clients</option>
                                </select>
                            </div>

                            <!-- Status -->
                            <div class="w-48">
                                <label class="block text-sm font-medium text-gray-700">
                                    Status
                                </label>

                                <select id="status_id"
                                        class="mt-1 block w-full h-9 rounded-md border-gray-300">
                                    <option value="">All Status</option>
                                </select>
                            </div>

                            <!-- Filter -->
                            <button type="button"
                                    id="filter-btn"
                                    class="h-9 px-4 bg-indigo-600 text-white rounded-md
                                        hover:bg-indigo-700">
                                <i class="fas fa-filter mr-1"></i>
                                Filter
                            </button>

                            <!-- Reset -->
                            <button type="button"
                                    id="reset-filter"
                                    class="h-9 px-4 bg-gray-200 text-gray-700 rounded-md
                                        hover:bg-gray-300">
                                Reset
                            </button>

                        </div>

                    </div>

                    @if ($invoices->count())

                        <div class="overflow-x-auto">

                            <table class="min-w-full divide-y divide-gray-200">

                                <thead class="bg-gray-50">
                                    <tr>

                                        <th class="px-6 py-3 text-left text-xs
                                                   font-medium text-gray-500
                                                   uppercase tracking-wider">
                                            Invoice Number
                                        </th>

                                        <th class="px-6 py-3 text-left text-xs
                                                   font-medium text-gray-500
                                                   uppercase tracking-wider">
                                            Client
                                        </th>

                                        <th class="px-6 py-3 text-left text-xs
                                                   font-medium text-gray-500
                                                   uppercase tracking-wider">
                                            Service
                                        </th>

                                        <th class="px-6 py-3 text-right text-xs
                                                   font-medium text-gray-500
                                                   uppercase tracking-wider">
                                            Quantity
                                        </th>

                                        <th class="px-6 py-3 text-right text-xs
                                                   font-medium text-gray-500
                                                   uppercase tracking-wider">
                                            VAT
                                        </th>

                                        <th class="px-6 py-3 text-right text-xs
                                                   font-medium text-gray-500
                                                   uppercase tracking-wider">
                                            Total
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs
                                                   font-medium text-gray-500
                                                   uppercase tracking-wider">
                                            STATUS
                                        </th>

                                        <th class="px-6 py-3 text-right text-xs
                                                   font-medium text-gray-500
                                                   uppercase tracking-wider">
                                            Actions
                                        </th>

                                    </tr>
                                </thead>

                                <tbody class="bg-white divide-y divide-gray-200">

                                    @foreach ($invoices as $invoice)

                                        <tr class="hover:bg-gray-50">

                                            {{-- Invoice Number --}}
                                            <td class="px-6 py-4 whitespace-nowrap">

                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $invoice->invoice_number }}
                                                </div>

                                                <div class="text-xs text-gray-500">
                                                    {{ $invoice->invoice_date?->format('d M Y') }}
                                                </div>

                                            </td>

                                            {{-- Client --}}
                                            <td class="px-6 py-4 whitespace-nowrap">

                                                <div class="text-sm text-gray-900">
                                                    {{ $invoice->client?->name ?? $invoice->client?->name ?? '-' }}
                                                </div>

                                            </td>

                                            {{-- Service --}}
                                            <td class="px-6 py-4">

                                                <div class="text-sm text-gray-900">
                                                    {{ $invoice->service?->name ?? $invoice->service?->name ?? '-' }}
                                                </div>

                                            </td>

                                            {{-- Quantity --}}
                                            <td class="px-6 py-4 whitespace-nowrap
                                                       text-right text-sm text-gray-600">

                                                {{ $invoice->quantity ?? $invoice->items->sum('quantity') }}

                                            </td>

                                            {{-- VAT --}}
                                            <td class="px-6 py-4 whitespace-nowrap
                                                       text-right text-sm text-gray-600">

                                                AED {{ number_format($invoice->vat, 2) }}

                                            </td>                                            

                                            {{-- Total --}}
                                            <td class="px-6 py-4 whitespace-nowrap
                                                       text-right">

                                                <span class="text-sm font-semibold text-gray-900">
                                                    AED {{ number_format($invoice->total, 2) }}
                                                </span>

                                            </td>

                                            {{-- Status --}}
                                            <td class="px-6 py-4 whitespace-nowrap">

                                                <div class="text-sm text-gray-900">
                                                    {{ $invoice->status?->name ?? $invoice->status?->name ?? '-' }}
                                                </div>

                                            </td>

                                            {{-- Actions --}}
                                            <td class="px-6 py-4 whitespace-nowrap
                                                       text-right text-sm">

                                                {{-- Print --}}
                                                <a
                                                    href="{{ route('invoices.print', $invoice->id) }}"
                                                    target="_blank"
                                                    class="text-gray-600 hover:text-gray-900 mr-3"
                                                >
                                                    Print
                                                </a>

                                                {{-- Edit --}}
                                                <a
                                                    href="{{ route('invoices.edit', $invoice->id) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 mr-3"
                                                >
                                                    Edit
                                                </a>

                                                {{-- Cancel --}}
                                                <form
                                                    action="{{ route('invoices.cancel', $invoice->id) }}"
                                                    method="POST"
                                                    class="inline"
                                                    onsubmit="return confirm('Are you sure you want to cancel this invoice?')"
                                                >

                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="text-red-600 hover:text-red-900"
                                                    >
                                                        Cancel
                                                    </button>

                                                </form>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                        {{-- Pagination --}}
                        <div class="mt-6">
                            {{ $invoices->links() }}
                        </div>

                    @else

                        <div class="text-center py-12">

                            <p class="text-sm text-gray-500">
                                No invoices found.
                            </p>

                            <a
                                href="{{ route('invoices.create') }}"
                                class="inline-flex mt-4 text-sm text-indigo-600
                                       hover:text-indigo-900"
                            >
                                Create your first invoice
                            </a>

                        </div>

                    @endif

                </div>

            </div>

        </div>
    </div>

</x-app-layout>