<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Receive Payment') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <form method="POST" action="{{ route('payments.store') }}">
                @csrf

                <div class="bg-white shadow-sm rounded-lg p-6">

                    {{-- Payment Details --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <div>
                            <label class="block font-medium text-sm text-gray-700">
                                Client
                            </label>

                            <select
                                name="client_id"
                                id="client_id"
                                class="mt-1 block w-full rounded-md border-gray-300"
                                required
                            >
                                <option value="">Select Client</option>

                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700">
                                Payment Date
                            </label>

                            <input
                                type="date"
                                name="payment_date"
                                value="{{ date('Y-m-d') }}"
                                class="mt-1 block w-full rounded-md border-gray-300"
                                required
                            >
                        </div>                        

                        <div>
                            <label class="block font-medium text-sm text-gray-700">
                                Payment Method
                            </label>

                            <select
                                name="payment_method"
                                class="mt-1 block w-full rounded-md border-gray-300"
                            >
                                <option value="Cash" selected>Cash</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Cheque">Cheque</option>
                                <option value="Card">Card</option>
                            </select>
                        </div>                        

                        <div>
                            <label class="block font-medium text-sm text-gray-700">
                                Notes
                            </label>

                            <textarea
                                name="notes"
                                rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300"
                            ></textarea>
                        </div>

                    </div>

                    {{-- Unpaid Invoices --}}
                    <div class="mt-8">

                        <h3 class="text-lg font-semibold mb-4">
                            Unpaid Invoices
                        </h3>

                        <div class="overflow-x-auto">

                            <table class="min-w-full border border-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left">
                                            Invoice
                                        </th>
                                        <th class="px-4 py-3 text-left">
                                            Date
                                        </th>
                                        <th class="px-4 py-3 text-right">
                                            Invoice Total
                                        </th>
                                        <th class="px-4 py-3 text-right">
                                            Paid
                                        </th>
                                        <th class="px-4 py-3 text-right">
                                            Balance
                                        </th>
                                        <th class="px-4 py-3 text-right">
                                            Payment
                                        </th>
                                    </tr>
                                </thead>

                                <tbody id="invoice-list">
                                    <tr>
                                        <td colspan="6"
                                            class="px-4 py-6 text-center text-gray-500">
                                            Select a client to view unpaid invoices.
                                        </td>
                                    </tr>
                                </tbody>

                                <tfoot>
                                    <tr class="bg-gray-50 font-semibold">
                                        <td colspan="5"
                                            class="px-4 py-3 text-right">
                                            Allocated:
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <span id="allocated-total">0.00</span>
                                        </td>
                                    </tr>
                                </tfoot>

                            </table>

                        </div>

                    </div>

                    {{-- Buttons --}}
                    <div class="mt-6 flex justify-end gap-3">

                        <a href="{{ route('payments') }}"
                           class="px-4 py-2 bg-gray-200 rounded-md">
                            Cancel
                        </a>

                        <button
                            type="submit"
                            id="save-payment"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md"
                        >
                            Save Payment
                        </button>

                    </div>

                </div>

            </form>

        </div>
    </div>


    <script>
        const clientSelect = document.getElementById('client_id');
        const invoiceList = document.getElementById('invoice-list');
        const unpaidInvoicesUrl = @json(
            route('payments.unpaid-invoices', ['client' => ':client'])
        );

        clientSelect.addEventListener('change', function () {

            const clientId = this.value;

            invoiceList.innerHTML = `
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center">
                        Loading...
                    </td>
                </tr>
            `;

            if (!clientId) {
                return;
            }

            fetch(unpaidInvoicesUrl.replace(':client', clientId))
                .then(response => response.json())
                .then(invoices => {

                    if (invoices.length === 0) {
                        invoiceList.innerHTML = `
                            <tr>
                                <td colspan="6"
                                    class="px-4 py-6 text-center text-gray-500">
                                    No unpaid invoices found.
                                </td>
                            </tr>
                        `;

                        return;
                    }

                    invoiceList.innerHTML = '';

                    invoices.forEach(invoice => {

                        invoiceList.innerHTML += `
                            <tr class="border-t">

                                <td class="px-4 py-3">
                                    ${invoice.invoice_number}
                                    <input
                                        type="hidden"
                                        name="invoice_ids[]"
                                        value="${invoice.id}"
                                    >
                                </td>

                                <td class="px-4 py-3">
                                    ${invoice.invoice_date}
                                </td>

                                <td class="px-4 py-3 text-right">
                                    ${Number(invoice.total).toFixed(2)}
                                </td>

                                <td class="px-4 py-3 text-right">
                                    ${Number(invoice.paid).toFixed(2)}
                                </td>

                                <td class="px-4 py-3 text-right">
                                    ${Number(invoice.balance).toFixed(2)}
                                    <button
                                        type="button"
                                        class="pay-balance-btn text-blue-600 hover:text-blue-800"
                                        data-invoice-id="${invoice.id}"
                                        data-balance="${invoice.balance}"
                                        title="Pay full balance"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-5 h-5"
                                            viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 12H9v-1H8v-2h1V9H8V7h2V6h2v1h1v2h-1v2h1v2h-2v1z"/>
                                        </svg>
                                    </button>
                                </td>

                                <td class="px-4 py-3">
                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        max="${invoice.balance}"
                                        name="amounts[${invoice.id}]"
                                        class="invoice-amount w-full rounded-md border-gray-300 text-right"
                                        data-balance="${invoice.balance}"
                                        value="0"
                                    >
                                </td>

                            </tr>
                        `;
                    });

                    attachAmountEvents();
                });
        });

        invoiceList.addEventListener('click', function (e) {

            const button = e.target.closest('.pay-balance-btn');

            if (!button) {
                return;
            }

            const invoiceId = button.dataset.invoiceId;
            const balance = parseFloat(button.dataset.balance) || 0;

            const amountInput = document.querySelector(
                `input[name="amounts[${invoiceId}]"]`
            );

            if (amountInput) {
                amountInput.value = balance.toFixed(2);

                // Update allocated total
                amountInput.dispatchEvent(
                    new Event('input', { bubbles: true })
                );
            }
        });

        document.getElementById('save-payment').addEventListener('click', function () {

            document.querySelectorAll('.invoice-amount').forEach(input => {

                const amount = parseFloat(input.value) || 0;

                if (amount <= 0) {
                    input.disabled = true;
                }
            });

        });


        function attachAmountEvents()
        {
            document.querySelectorAll('.invoice-amount')
                .forEach(input => {

                    input.addEventListener('input', calculateTotal);

                });
        }


        function calculateTotal()
        {
            let total = 0;

            document.querySelectorAll('.invoice-amount')
                .forEach(input => {

                    let value = parseFloat(input.value) || 0;
                    let balance = parseFloat(input.dataset.balance);

                    if (value > balance) {
                        value = balance;
                        input.value = balance.toFixed(2);
                    }

                    total += value;
                });

            document.getElementById('allocated-total')
                .textContent = total.toFixed(2);
        }
    </script>

</x-app-layout>