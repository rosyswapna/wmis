<x-app-layout>

   
                <x-slot name="header">
                {{ __('Services') }}
                </x-slot>
           

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">
                                Service ({{ $services->total() }})
                            </h3>

                            <p class="mt-1 text-sm text-gray-600">
                                Manage your company clients.
                            </p>
                        </div>

                        <x-table-link href="{{ route('services.create') }}">
                            {{ __('Create Service') }}
                        </x-table-link>
                        
                    </div>

                    @if ($services->count())

                        <div class="overflow-x-auto">

                            <table class="min-w-full divide-y divide-gray-200">

                                <thead class="bg-gray-50">

                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium
                                                   text-gray-500 uppercase tracking-wider">
                                            #
                                        </th>

                                        <th class="px-6 py-3 text-left text-xs font-medium
                                                   text-gray-500 uppercase tracking-wider">
                                            Name
                                        </th>

                                        <th class="px-6 py-3 text-left text-xs font-medium
                                                   text-gray-500 uppercase tracking-wider">
                                            Description
                                        </th>

                                        <th class="px-6 py-3 text-left text-xs font-medium
                                                   text-gray-500 uppercase tracking-wider">
                                            Standard Price
                                        </th> 
                                        <th class="px-6 py-3 text-left text-xs font-medium
                                                   text-gray-500 uppercase tracking-wider">
                                            Invoice Number
                                        </th>                                        

                                        <th class="px-6 py-3 text-right text-xs font-medium
                                                   text-gray-500 uppercase tracking-wider">
                                            Action
                                        </th>
                                    </tr>

                                </thead>

                                <tbody class="bg-white divide-y divide-gray-200">

                                    @foreach ($services as $service)

                                        <tr class="hover:bg-gray-50">

                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $services->firstItem() + $loop->index }}
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $service->name }}
                                                </div>
                                            </td>

                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ $service->description ?? '-' }}
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {{ $service->standard_price ?? '-' }}
                                            </td> 
                                            
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                               @if ($service->auto_invoice_number)
                                                    <span class="text-green-600 font-medium">
                                                        Automatic
                                                    </span>
                                                @else
                                                    <span class="text-gray-600">
                                                        Manual
                                                    </span>
                                                @endif
                                            </td> 

                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">

                                                <a href="{{ route('services.edit', $service->id) }}"
                                                   class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                    Edit
                                                </a>

                                                <form
                                                    action="{{ route('services.delete', $service->id) }}"
                                                    method="POST"
                                                    class="inline"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        onclick="return confirm('Are you sure you want to delete this service?')"
                                                        class="text-red-600 hover:text-red-900"
                                                    >
                                                        Delete
                                                    </button>
                                                </form>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                        <div class="mt-6">
                            {{ $services->links() }}
                        </div>

                    @else

                        <div class="text-center py-12">

                            <p class="text-sm text-gray-500">
                                No services found.
                            </p>

                            <a href="{{ route('services.create') }}"
                               class="inline-flex mt-4 text-sm text-indigo-600 hover:text-indigo-900">
                                Add your first service
                            </a>

                        </div>

                    @endif

                </div>

            </div>

        </div>
    </div>

</x-app-layout>