<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Invoice') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-lg">
                <div class="p-6">

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="mb-5 rounded-md bg-red-50 border border-red-200 p-4">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" id="invoice-form"
                          action="{{ route('invoices.store') }}">

                        @csrf

                        <!-- Invoice Details -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <!-- Invoice Date -->
                            <div>
                                <x-input-label value="Invoice Date"/>
                                <x-text-input
                                    required
                                    type="date"
                                    name="invoice_date"
                                    class="mt-1 block w-full"
                                    value="{{ old('invoice_date', date('Y-m-d')) }}"
                                />
                                <x-input-error :messages="$errors->get('invoice_date')" />
                            </div>

                            <!-- Due Date -->
                            <div>
                                <x-input-label value="Due Date"/>
                                <x-text-input
                                    type="date"
                                    name="due_date"
                                    class="mt-1 block w-full"
                                    value="{{ old('due_date') }}"
                                />
                            </div>                           

                        </div>

                        <!-- Client & Service -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">

                            <div>
                                <x-input-label value="Client"/>

                                <select name="client_id" required
                                        class="mt-1 block w-full border-gray-300 rounded-md">

                                    <option value="">Select Client</option>

                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}">
                                            {{ $client->name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <div>
                                <x-input-label value="Service"/>

                                <select id="service_id" required
                                        name="service_id"
                                        class="mt-1 block w-full border-gray-300 rounded-md">

                                    <option value="">Select Service</option>

                                    @foreach($services as $service)
                                        <option
                                            value="{{ $service->id }}"
                                            data-invauto="{{ $service->auto_invoice_number ? '1' : '0' }}"
                                            data-price="{{ $service->standard_price }}">
                                            {{ $service->name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                        </div>

                        <!-- Invoice Number -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">

                            <div id="invoice_number_wrapper">
                                <x-input-label value="Invoice Number"/>
                                <x-text-input
                                    id="invoice_number"
                                    name="invoice_number"
                                    class="mt-1 block w-full"
                                    value="{{ old('invoice_number','') }}"
                                />
                            </div>  
                            
                            <div>
                                <x-input-label value="Unit Price (Inclusive VAT 5%)"/>

                                <x-text-input
                                    id="unit_price"
                                    type="number"
                                    step="0.01"
                                    name="unit_price"
                                    class="mt-1 block w-full"
                                />
                            </div>

                        </div>
                        

                        <!-- Workers -->
                        <div class="mt-6">

                            <div class="flex justify-between items-center mb-3">
                                <h3 class="font-semibold">
                                    Workers
                                </h3>

                                <button type="button"
                                        id="addWorker"
                                        class="bg-nblue-600 text-white px-3 py-2 rounded">
                                    Add Worker
                                </button>
                            </div>

                            <table class="w-full border">

                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="border p-2">EMR Number</th>
                                        <th class="border p-2">Worker Name</th>
                                        <th class="border p-2">Action</th>
                                    </tr>
                                </thead>

                                <!-- <tbody id="workerTable">

                                    <tr>
                                        <td class="border p-2">
                                            <input
                                                type="text"
                                                name="items[0][emr_number]"
                                                class="w-full border-gray-300 rounded"
                                                required
                                            >
                                            <input
                                                type="hidden"
                                                name="items[0][worker_id]"
                                                class="w-full border-gray-300 rounded"
                                                required
                                            >
                                        </td>
                                        <td class="border p-2">
                                            <input
                                                type="text"
                                                name="items[0][worker_name]"
                                                class="w-full border-gray-300 rounded"
                                                required
                                            >
                                        </td>

                                        <td class="border p-2 text-center">
                                            -
                                        </td>
                                    </tr>

                                </tbody> -->
                                <tbody id="workerTable">

                                    <tr class="worker-row">
                                        <td class="border p-2">
                                            <input
                                                type="text"
                                                name="items[0][emr_number]"
                                                class="worker-emr w-full border-gray-300 rounded"
                                                autocomplete="off"
                                                required
                                                data-worker-url="{{ route('worker.by-emr', ['emrNumber' => '__EMR__']) }}"
                                            >

                                            <input
                                                type="hidden"
                                                name="items[0][worker_id]"
                                                class="worker-id"
                                            >
                                        </td>

                                        <td class="border p-2">
                                            <input
                                                type="text"
                                                name="items[0][worker_name]"
                                                class="worker-name w-full border-gray-300 rounded"
                                                required
                                            >
                                        </td>

                                        <td class="border p-2 text-center worker-status">
                                            -
                                        </td>
                                    </tr>

                                </tbody>

                                <tfoot class="bg-gray-100 font-semibold">
                                    <tr>
                                        <td colspan="1" class="border p-3 text-right">
                                            Total Workers / Quantity
                                        </td>

                                        <td class="border p-3 text-right">
                                            <span id="total_quantity">1</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="1" class="border p-3 text-right">
                                            Net Total
                                        </td>

                                        <td class="border p-3 text-right">
                                            <span id="net_total">0.00</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="1" class="border p-3 text-right">
                                            Inclusive VAT (5%)
                                        </td>

                                        <td class="border p-3 text-right">
                                            <span id="vat_total">0.00</span>
                                        </td>
                                    </tr>

                                    <!-- <tr>
                                        <td colspan="1" class="border p-3 text-right">
                                            Discount
                                        </td>
                                        <td class="border p-3 text-right">
                                            <x-text-input
                                                type="number"
                                                step="0.01"
                                                name="discount"
                                                class="mt-1 w-32 text-right inline-block"
                                                value="0"/>
                                        </td>
                                    </tr> -->

                                    <tr class="text-lg">
                                        <td colspan="1" class="border p-3 text-right">
                                            Total
                                        </td>

                                        <td class="border p-3 text-right">
                                            <span id="grand_total">0.00</span>
                                        </td>
                                    </tr>
                                </tfoot>

                            </table>

                        </div>

                        <div class="mt-6 flex items-center gap-3">

                            <x-primary-button
                                id="submit-btn"
                                type="submit"
                                name="status"
                                value="New Invoice">
                                Save Invoice
                            </x-primary-button>

                            <x-secondary-button
                                id="draft-btn"
                                type="button"
                                name="draft"
                                value="Draft">
                                Draft Invoice
                            </x-secondary-button>

                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</x-app-layout>

<script>
let row = 1;

document.getElementById('addWorker').addEventListener('click', function () {

    let html = `
        <tr class="worker-row">
            <td class="border p-2">
                <input
                    type="text"
                    name="items[${row}][emr_number]"
                    class="worker-emr w-full border-gray-300 rounded"
                    autocomplete="off"
                    required
                    data-worker-url="{{ route('worker.by-emr', ['emrNumber' => '__EMR__']) }}"
                >

                <input
                    type="hidden"
                    name="items[${row}][worker_id]"
                    class="worker-id"
                >
            </td>
            <td class="border p-2">
                <input
                    type="text"
                    name="items[${row}][worker_name]"
                    class="worker-name w-full border-gray-300 rounded"
                    required>
            </td>

            <td class="border p-2 text-center">
                <button type="button"
                        class="text-red-600 removeRow">
                    Remove
                </button>
            </td>
        </tr>
    `;

    document.getElementById('workerTable')
        .insertAdjacentHTML('beforeend', html);

    row++;
    calculateTotals();
});

document.addEventListener('click', function(e){

    if(e.target.classList.contains('removeRow')){
        e.target.closest('tr').remove();
        calculateTotals();
    }

});

document.getElementById('service_id').addEventListener('change', function () { 
        
        let price =
            this.options[this.selectedIndex]
                .dataset.price;
        document.getElementById('unit_price').value =
            price ?? 0;

        let invAuto =
            this.options[this.selectedIndex]
                .dataset.invauto;
        
        const invoiceNumberWrapper = document.getElementById('invoice_number_wrapper');
        const draftBtn = document.getElementById('draft-btn');
        const invoiceNumber = document.getElementById('invoice_number');
        if(invAuto === '1'){
            invoiceNumberWrapper.style.display = 'none';
            draftBtn.disabled = false;
            invoiceNumber.required = false;
        }else{
            invoiceNumberWrapper.style.display = 'block';
            draftBtn.disabled = true;
            invoiceNumber.required = true;
        } 
        
        calculateTotals();
        
});

document.getElementById('unit_price')
    .addEventListener('input', calculateTotals);

// document.querySelector('[name="discount"]')
//     .addEventListener('input', calculateTotals);

document.getElementById('draft-btn').addEventListener('click', async function () {

    const button = this;
    const form = document.getElementById('invoice-form');

    button.disabled = true;
    button.innerText = 'Drafting Invoice...';

    try {
        const formData = new FormData(form);
        const response = await fetch('{{ route('invoices.draft') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.message || 'Failed to save draft.');
        }

        if (data.success) {
            window.location.href = data.redirect;
        }

    } catch (error) {
        console.error(error);
        alert(error.message);
        button.disabled = false;
        button.innerText = 'Draft Invoice';
    }
});

document.getElementById('invoice-form').addEventListener('submit', function () {
    const submitBtn = document.getElementById('submit-btn');

    // Prevent multiple submissions
    if (submitBtn.disabled) {
        return false;
    }

    submitBtn.disabled = true;
    submitBtn.innerText = 'Saving...';
});

document.addEventListener('DOMContentLoaded', function () {


    document.addEventListener('blur', function (e) {

        if (
            !e.target.classList.contains('worker-emr') ||
            e.target.value.trim() === ''
        ) {
            return;
        }

        const currentInput = e.target;
        const currentEmr = currentInput.value.trim();

        // Check duplicate EMR in the current invoice
        const duplicate = [...document.querySelectorAll('.worker-emr')]
            .some(input =>
                input !== currentInput &&
                input.value.trim() === currentEmr
            );

        if (duplicate) {
            alert(`EMR number ${currentEmr} is already added.`);

            currentInput.value = '';
            currentInput.focus();

            return;
        }

        // Lookup worker
        findWorker(currentInput);

    }, true);
});


function findWorker(emrInput)
{
    const row = emrInput.closest('.worker-row');

    const nameInput = row.querySelector('.worker-name');
    const workerId = row.querySelector('.worker-id');

    const emrNumber = emrInput.value.trim();

    if (!emrNumber) {
        return;
    }

    status.textContent = 'Searching...';
    const workerUrl = emrInput.dataset.workerUrl.replace(
        '__EMR__',
        encodeURIComponent(emrNumber)
    );

    fetch(workerUrl,
        {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector(
                    'meta[name="csrf-token"]'
                ).getAttribute('content')
            }
        }
    )
    .then(response => response.json())
    .then(data => {

        if (data.found) {

            workerId.value = data.worker.id;
            nameInput.value = data.worker.name;

            // Existing worker - don't allow changing name
            nameInput.readOnly = true;

            status.textContent = 'Existing worker';

        } else {

            workerId.value = '';

            nameInput.value = '';
            nameInput.readOnly = false;

            status.textContent = 'New worker';

            nameInput.focus();
        }

    })
    .catch(error => {

        console.error(error);

        status.textContent = 'Unable to find worker';
    });
}


function calculateTotals() { 

    let quatity =
        parseFloat(document.querySelectorAll(
            '#workerTable input[name*="[worker_name]"]'
        ).length);
    let unitPrice =
        parseFloat(document.getElementById('unit_price').value) || 0;
    // let discount =
    //     parseFloat(document.querySelector('[name="discount"]').value) || 0;
    let discount = 0;

    let total = unitPrice * quatity;
    let netTotal = unitPrice / 1.05 * quatity;
    let vat = total - netTotal;     
    let grand_total = netTotal+vat - discount;

    document.getElementById('total_quantity').textContent = quatity;
    document.getElementById('net_total').textContent = netTotal.toFixed(2);
    document.getElementById('vat_total').textContent = vat.toFixed(2);
    document.getElementById('grand_total').textContent = grand_total.toFixed(2);
}
</script>