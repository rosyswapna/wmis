<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p3">

                <div class="p-6 text-gray-900 font-bold text-xl">
                    {{ __("Welcome to Worker Med Invoice System!") }}
                </div>

                @role('accountant')

                    <div class="flex gap-6 w-full">

                        <div class="flex-1 min-w-0">
                            <x-dashboard-tile
                                title="Clients"
                                id="client-count"
                                icon="fas fa-users"
                                icon-bg="bg-blue-100"
                                icon-color="text-blue-600"
                            />
                        </div>

                        <div class="flex-1 min-w-0">
                            <x-dashboard-tile
                                title="Drafts Pending"
                                id="draft-count"
                                icon="fas fa-hand-holding-medical"
                                icon-bg="bg-green-100"
                                icon-color="text-green-600"
                            />
                        </div>

                        <div class="flex-1 min-w-0">
                            <x-dashboard-tile
                                title="Today's Invoices"
                                id="invoice-count"
                                icon="fas fa-file-invoice"
                                icon-bg="bg-purple-100"
                                icon-color="text-purple-600"
                            />
                        </div>

                        <div class="flex-1 min-w-0">
                            <x-dashboard-tile
                                title="Total Invoice"
                                id="total-invoice"
                                icon="fas fa-file-invoice-dollar"
                                icon-bg="bg-yellow-100"
                                icon-color="text-yellow-600"
                            />
                        </div>

                    </div>

                    <div class="mt-6 bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            Monthly Sales
                        </h3>
                        <div style="height: 350px;">
                            <canvas id="monthlySalesChart"></canvas>
                        </div>
                    </div>

                @endrole

            </div>

        </div>
    </div>

    @role('accountant')
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                fetch('{{ route('dashboard.stats') }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to load dashboard statistics');
                    }

                    return response.json();
                })
                .then(data => {

                    // document.getElementById('client-count').textContent =
                    //     data.client_count;

                    // document.getElementById('service-count').textContent =
                    //     data.service_count;

                    // document.getElementById('today-invoice-count').textContent =
                    //     data.today_invoice_count;

                    // document.getElementById('total-invoice-amount').textContent =
                    //     'AED ' + data.total_invoice_amount;
                    document.querySelectorAll('.dashboard-tile-value').forEach(function (element) {

                        const key = element.id.replace(/-/g, '_');

                        if (data[key] !== undefined) {
                            element.textContent = data[key];
                        } else {
                            element.textContent = '-';
                        }

                    });
                })
                .catch(error => {

                    console.error(error);

                    document.getElementById('client-count').textContent = '-';
                    document.getElementById('service-count').textContent = '-';
                    document.getElementById('today-invoice-count').textContent = '-';
                    document.getElementById('total-invoice-amount').textContent = '-';

                });

            });

            document.addEventListener('DOMContentLoaded', function () {

                fetch('{{ route('dashboard.monthly-sales') }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to load monthly sales');
                    }

                    return response.json();
                })
                .then(data => {

                    const labels = data.map(item => item.month);
                    const totals = data.map(item => item.total);

                    new Chart(document.getElementById('monthlySalesChart'), {
                        type: 'bar',

                        data: {
                            labels: labels,

                            datasets: [{
                                label: 'Sales',
                                data: totals,
                                borderWidth: 1
                            }]
                        },

                        options: {
                            responsive: true,
                            maintainAspectRatio: false,

                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return 'AED ' +
                                                Number(value).toLocaleString();
                                        }
                                    }
                                }
                            },

                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return 'AED ' +
                                                Number(context.raw)
                                                .toLocaleString(undefined, {
                                                    minimumFractionDigits: 2
                                                });
                                        }
                                    }
                                }
                            }
                        }
                    });

                })
                .catch(error => {
                    console.error(error);
                });

            });
        </script>

        
    @endrole

</x-app-layout>