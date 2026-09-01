<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Workers Report') }}
            </h2>            
        </div>
    </x-slot>

    <div x-data="{ filterOpen: {{ request()->hasAny(['worker_name', 'date_from', 'date_to']) ? 'true' : 'false' }} }" class="py-8">
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
                                Workers ({{ $reportData->total() }})
                            </h3>

                            <p class="mt-1 text-sm text-gray-600">
                                Workers invoice information.
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

                            <a href="{{ route('reports.workers.export', request()->query()) }}"
                            class="inline-flex items-center justify-center
                                    w-9 h-9 rounded-md px-4 py-2
                                    text-gray-600 hover:text-gray-900
                                    hover:bg-gray-100"
                            title="Export Excel">

                                <i class="fas fa-file-export"></i>

                            </a>
                        </div>
                    </div>

                    <!-- Filter Section -->
                    <div x-show="filterOpen"
                        x-transition
                        x-cloak
                        class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-lg">

                        <form method="GET" action="{{ route('reports.workers') }}">


                            <div class="flex items-end gap-3">
                                <!-- Worker Name -->
                                <div class="w-48">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Worker Name
                                    </label>

                                    <input type="text"
                                        id="worker_name" name="worker_name"
                                        value="{{ request('worker_name') }}"
                                        class="mt-1 block w-full h-9 rounded-md border-gray-300">
                                </div>


                                <!-- From Date -->
                                <div class="w-48">
                                    <label class="block text-sm font-medium text-gray-700">
                                        From Date
                                    </label>

                                    <input type="date"
                                        id="date_from" name="date_from"
                                        value="{{ request('date_from') }}"
                                        class="mt-1 block w-full h-9 rounded-md border-gray-300">
                                </div>

                                <!-- To Date -->
                                <div class="w-48">
                                    <label class="block text-sm font-medium text-gray-700">
                                        To Date
                                    </label>

                                    <input type="date"
                                        id="date_to" name="date_to"
                                        value="{{ request('date_to') }}"
                                        class="mt-1 block w-full h-9 rounded-md border-gray-300">
                                </div>

                                <!-- Filter -->
                                <button type="submit"
                                        id="search-btn"
                                        class="h-9 px-4 bg-indigo-600 text-white rounded-md
                                            hover:bg-indigo-700">
                                    <i class="fas fa-search mr-1"></i>
                                    Search
                                </button>

                                <!-- Reset -->
                                <a href="{{ route('reports.workers') }}"
                                class="h-9 pt-2 px-4 bg-gray-200 text-gray-700 rounded-md
                                            hover:bg-gray-300">
                                    Clear
                                </a>

                            </div>
                        </form>

                    </div>

                    @if ($reportData->count())

                        <div class="overflow-x-auto">

                            <table class="min-w-full divide-y divide-gray-200">

                                <thead class="bg-gray-50">
                                    <tr>
                                        @foreach ($reportColumns as $col)
                                        <th class="px-6 py-3 text-left text-xs
                                                   font-medium text-gray-500
                                                   uppercase tracking-wider">
                                            {{$col}}
                                        </th>
                                        @endforeach
                                    </tr>
                                </thead>

                                <tbody class="bg-white divide-y divide-gray-200">

                                    @foreach ($reportData as $data)
                                        <tr class="hover:bg-gray-50">
                                            @foreach ($reportColumns as $key=>$col)
                                            <td class="px-6 py-4 whitespace-nowrap">

                                                <div class="text-sm text-gray-900">
                                                    {{ $data->$key }}
                                                </div>

                                            </td>
                                            @endforeach
                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                        {{-- Pagination --}}
                        <div class="mt-6">
                            {{ $reportData->links() }}
                        </div>

                    @else

                        <div class="text-center py-12">

                            <p class="text-sm text-gray-500">
                                No data found.
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>
    </div>

</x-app-layout>