<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Service') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <header class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ __('Service Information') }}
                        </h3>
                    </header>

                    <form method="POST"
                          action="{{ route('services.update', $service->id) }}"
                          class="space-y-6">

                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label
                                for="name"
                                :value="__('Service Item Name')"
                            />

                            <x-text-input
                                id="name"
                                name="name"
                                type="text"
                                class="mt-1 block w-full"
                                :value="old('name', $service->name)"
                                required
                                autofocus
                            />

                            <x-input-error
                                class="mt-2"
                                :messages="$errors->get('name')"
                            />
                        </div>

                        <div>
                            <x-input-label
                                for="description"
                                :value="__('Description')"
                            />

                            <textarea
                                id="description"
                                name="description"
                                rows="4"
                                class="mt-1 block w-full border-gray-300
                                       focus:border-indigo-500 focus:ring-indigo-500
                                       rounded-md shadow-sm"
                            >{{ old('description', $service->description) }}</textarea>

                            <x-input-error
                                class="mt-2"
                                :messages="$errors->get('description')"
                            />
                        </div>

                        <div>
                            <x-input-label
                                for="standard_price"
                                :value="__('Standard Price')"
                            />

                            <x-text-input
                                id="standard_price"
                                name="standard_price"
                                type="number"
                                step="0.01"
                                min="0"
                                class="mt-1 block w-full"
                                :value="old('standard_price', $service->standard_price)"
                                required
                            />

                            <x-input-error
                                class="mt-2"
                                :messages="$errors->get('standard_price')"
                            />
                        </div>

                        <div>
                            <label class="inline-flex items-center">

                                <input
                                    type="checkbox"
                                    name="auto_invoice_number"
                                    value="1"
                                    class="rounded border-gray-300
                                           text-indigo-600 shadow-sm
                                           focus:ring-indigo-500"
                                    @checked(old(
                                        'auto_invoice_number',
                                        $service->auto_invoice_number
                                    ))
                                >

                                <span class="ms-2 text-sm text-gray-600">
                                    {{ __('Auto Invoice Number Generation') }}
                                </span>

                            </label>
                        </div>

                        <div class="flex items-center gap-4 pt-4">

                            <x-primary-button>
                                {{ __('Update Service') }}
                            </x-primary-button>

                            <a
                                href="{{ route('services') }}"
                                class="inline-flex items-center px-4 py-2
                                       bg-white border border-gray-300
                                       rounded-md font-semibold text-xs
                                       text-gray-700 uppercase tracking-widest
                                       shadow-sm hover:bg-gray-50"
                            >
                                {{ __('Cancel') }}
                            </a>

                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</x-app-layout>