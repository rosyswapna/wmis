<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Client') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <header class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ __('Client Information') }}
                        </h3>

                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Enter the client company details.') }}
                        </p>
                    </header>

                    <form method="POST"
                          action="{{ route('clients.store') }}"
                          class="space-y-6">

                        @csrf

                        {{-- Client Name --}}
                        <div>
                            <x-input-label
                                for="name"
                                :value="__('Name')"
                            />

                            <x-text-input
                                id="name"
                                name="name"
                                type="text"
                                class="mt-1 block w-full"
                                :value="old('name')"
                                required
                                autofocus
                            />

                            <x-input-error
                                class="mt-2"
                                :messages="$errors->get('name')"
                            />
                        </div>

                        {{-- Address --}}
                        <div>
                            <x-input-label
                                for="address"
                                :value="__('Address')"
                            />

                            <textarea
                                id="address"
                                name="address"
                                rows="3"
                                class="mt-1 block w-full border-gray-300
                                       focus:border-indigo-500 focus:ring-indigo-500
                                       rounded-md shadow-sm"
                            >{{ old('address') }}</textarea>

                            <x-input-error
                                class="mt-2"
                                :messages="$errors->get('address')"
                            />
                        </div>

                        {{-- Country / State / City --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                            {{-- Country --}}
                            <div>
                                <x-input-label
                                    for="country_id"
                                    :value="__('Country')"
                                />

                                <select
                                    id="country_id"
                                    name="country_id"
                                    class="mt-1 block w-full border-gray-300
                                           focus:border-indigo-500 focus:ring-indigo-500
                                           rounded-md shadow-sm"
                                    required
                                >
                                    <option value="">
                                        Select Country
                                    </option>

                                    @foreach ($countries as $country)
                                        <option
                                            value="{{ $country->id }}"
                                            @selected(old('country_id') == $country->id)
                                        >
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <x-input-error
                                    class="mt-2"
                                    :messages="$errors->get('country_id')"
                                />
                            </div>

                            {{-- State --}}
                            <div>
                                <x-input-label
                                    for="state_id"
                                    :value="__('State')"
                                />

                                <select
                                    id="state_id"
                                    name="state_id"
                                    class="mt-1 block w-full border-gray-300
                                           focus:border-indigo-500 focus:ring-indigo-500
                                           rounded-md shadow-sm"
                                    required
                                >
                                    <option value="">
                                        Select State
                                    </option>

                                    @foreach ($states as $state)
                                        <option
                                            value="{{ $state->id }}"
                                            @selected(old('state_id') == $state->id)
                                        >
                                            {{ $state->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <x-input-error
                                    class="mt-2"
                                    :messages="$errors->get('state_id')"
                                />
                            </div>

                            {{-- City --}}
                            <div>
                                <x-input-label
                                    for="city_id"
                                    :value="__('City')"
                                />

                                <select
                                    id="city_id"
                                    name="city_id"
                                    class="mt-1 block w-full border-gray-300
                                           focus:border-indigo-500 focus:ring-indigo-500
                                           rounded-md shadow-sm"
                                    required
                                >
                                    <option value="">
                                        Select City
                                    </option>

                                    @foreach ($cities as $city)
                                        <option
                                            value="{{ $city->id }}"
                                            @selected(old('city_id') == $city->id)
                                        >
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <x-input-error
                                    class="mt-2"
                                    :messages="$errors->get('city_id')"
                                />
                            </div>

                        </div>

                        {{-- Buttons --}}
                        <div class="flex items-center gap-4 pt-4">

                            <x-primary-button>
                                {{ __('Save Client') }}
                            </x-primary-button>

                            <a
                                href="{{ route('clients') }}"
                                class="inline-flex items-center px-4 py-2
                                       bg-white border border-gray-300
                                       rounded-md font-semibold text-xs
                                       text-gray-700 uppercase tracking-widest
                                       shadow-sm hover:bg-gray-50
                                       focus:outline-none focus:ring-2
                                       focus:ring-indigo-500 focus:ring-offset-2"
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