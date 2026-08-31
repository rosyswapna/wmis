<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Hospital') }}
        </h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg">

                <div class="p-6">
                    
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Hospital / Clinic Information') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Update your hospital or clinic business and invoice information.') }}
                            </p>
                        </header>

                        <form method="post"
                            action="{{ route('hospital.store', $hospital->id) }}"
                            class="mt-6 space-y-6"
                            enctype="multipart/form-data">

                            @csrf
                            @method('patch')

                            {{-- Hospital Information --}}
                            <div>
                                <x-input-label
                                    for="hospital_name"
                                    :value="__('Hospital / Clinic Name')"
                                />

                                <x-text-input
                                    id="hospital_name"
                                    name="hospital_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    :value="old('hospital_name', $hospital->hospital_name)"
                                    required
                                    autofocus
                                />

                                <x-input-error
                                    class="mt-2"
                                    :messages="$errors->get('hospital_name')"
                                />
                            </div>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                                {{-- Trade License --}}
                                <div>
                                    <x-input-label
                                        for="trade_license_number"
                                        :value="__('Trade License Number')"
                                    />

                                    <x-text-input
                                        id="trade_license_number"
                                        name="trade_license_number"
                                        type="text"
                                        class="mt-1 block w-full"
                                        :value="old('trade_license_number', $hospital->trade_license_number)"
                                    />

                                    <x-input-error
                                        class="mt-2"
                                        :messages="$errors->get('trade_license_number')"
                                    />
                                </div>

                                {{-- Ownership --}}
                                <div>
                                    <x-input-label
                                        for="ownership_type"
                                        :value="__('Ownership Type')"
                                    />

                                    <select
                                        id="ownership_type"
                                        name="ownership_type"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    >
                                        <option value="">Select Ownership Type</option>

                                        <option value="Private"
                                            @selected(old('ownership_type', $hospital->ownership_type) === 'Private')>
                                            Private
                                        </option>

                                        <option value="Government"
                                            @selected(old('ownership_type', $hospital->ownership_type) === 'Government')>
                                            Government
                                        </option>

                                        <option value="Partnership"
                                            @selected(old('ownership_type', $hospital->ownership_type) === 'Partnership')>
                                            Partnership
                                        </option>
                                    </select>

                                    <x-input-error
                                        class="mt-2"
                                        :messages="$errors->get('ownership_type')"
                                    />
                                </div>

                                {{-- Tax Registration --}}
                                <div>
                                    <x-input-label
                                        for="tax_registration_number"
                                        :value="__('Tax Registration Number')"
                                    />

                                    <x-text-input
                                        id="tax_registration_number"
                                        name="tax_registration_number"
                                        type="text"
                                        class="mt-1 block w-full"
                                        :value="old('tax_registration_number', $hospital->tax_registration_number)"
                                    />

                                    <x-input-error
                                        class="mt-2"
                                        :messages="$errors->get('tax_registration_number')"
                                    />
                                </div>

                                {{-- Telephone --}}
                                <div>
                                    <x-input-label
                                        for="telephone_number"
                                        :value="__('Telephone Number')"
                                    />

                                    <x-text-input
                                        id="telephone_number"
                                        name="telephone_number"
                                        type="text"
                                        class="mt-1 block w-full"
                                        :value="old('telephone_number', $hospital->telephone_number)"
                                    />

                                    <x-input-error
                                        class="mt-2"
                                        :messages="$errors->get('telephone_number')"
                                    />
                                </div>

                                {{-- Email --}}
                                <div>
                                    <x-input-label
                                        for="email"
                                        :value="__('Email')"
                                    />

                                    <x-text-input
                                        id="email"
                                        name="email"
                                        type="email"
                                        class="mt-1 block w-full"
                                        :value="old('email', $hospital->email)"
                                    />

                                    <x-input-error
                                        class="mt-2"
                                        :messages="$errors->get('email')"
                                    />
                                </div>

                                {{-- Website --}}
                                <div>
                                    <x-input-label
                                        for="website"
                                        :value="__('Website')"
                                    />

                                    <x-text-input
                                        id="website"
                                        name="website"
                                        type="url"
                                        class="mt-1 block w-full"
                                        :value="old('website', $hospital->website)"
                                    />

                                    <x-input-error
                                        class="mt-2"
                                        :messages="$errors->get('website')"
                                    />
                                </div>

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
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                >{{ old('address', $hospital->address) }}</textarea>

                                <x-input-error
                                    class="mt-2"
                                    :messages="$errors->get('address')"
                                />
                            </div>

                            {{-- Location --}}
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

                                {{-- Country --}}
                                <div>
                                    <x-input-label
                                        for="country_id"
                                        :value="__('Country')"
                                    />

                                    <select
                                        id="country_id"
                                        name="country_id"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    >
                                        <option value="">Select Country</option>

                                        @foreach ($countries as $country)
                                            <option
                                                value="{{ $country->id }}"
                                                @selected(old('country_id', $hospital->country_id) == $country->id)
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
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    >
                                        <option value="">Select State</option>

                                        @foreach ($states as $state)
                                            <option
                                                value="{{ $state->id }}"
                                                @selected(old('state_id', $hospital->state_id) == $state->id)
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
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    >
                                        <option value="">Select City</option>

                                        @foreach ($cities as $city)
                                            <option
                                                value="{{ $city->id }}"
                                                @selected(old('city_id', $hospital->city_id) == $city->id)
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

                            {{-- Invoice Settings --}}
                            <div class="pt-4 border-t border-gray-200">

                                <h3 class="text-md font-medium text-gray-900">
                                    {{ __('Invoice Settings') }}
                                </h3>

                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __('Configure the prefix used for invoice numbers.') }}
                                </p>

                                <div class="mt-4">
                                    <x-input-label
                                        for="invoice_number_prefix"
                                        :value="__('Invoice Number Prefix')"
                                    />

                                    <x-text-input
                                        id="invoice_number_prefix"
                                        name="invoice_number_prefix"
                                        type="text"
                                        maxlength="20"
                                        class="mt-1 block w-full"
                                        :value="old('invoice_number_prefix', $hospital->invoice_number_prefix)"
                                    />

                                    <x-input-error
                                        class="mt-2"
                                        :messages="$errors->get('invoice_number_prefix')"
                                    />
                                </div>

                            </div>

                            {{-- Logo & Seal --}}
                            <div class="pt-4 border-t border-gray-200">

                                <h3 class="text-md font-medium text-gray-900">
                                    {{ __('Branding') }}
                                </h3>

                                <div class="mt-4 grid grid-cols-1 gap-6 md:grid-cols-2">

                                    {{-- Logo --}}
                                    <div>
                                        <x-input-label
                                            for="logo"
                                            :value="__('Logo')"
                                        />

                                        <input
                                            id="logo"
                                            name="logo"
                                            type="file"
                                            accept="image/*"
                                            class="mt-1 block w-full text-sm text-gray-600"
                                        />

                                        @if ($hospital->logo)
                                            <div class="mt-3 bg-gray-300">
                                                <img
                                                    src="{{ asset('storage/' . $hospital->logo) }}"
                                                    alt="Hospital Logo"
                                                    class="h-20 w-auto object-contain"
                                                >
                                            </div>
                                        @endif

                                        <x-input-error
                                            class="mt-2"
                                            :messages="$errors->get('logo')"
                                        />
                                    </div>

                                    {{-- Company Seal --}}
                                    <div>
                                        <x-input-label
                                            for="company_seal"
                                            :value="__('Company Seal')"
                                        />

                                        <input
                                            id="company_seal"
                                            name="company_seal"
                                            type="file"
                                            accept="image/*"
                                            class="mt-1 block w-full text-sm text-gray-600"
                                        />

                                        @if ($hospital->company_seal)
                                            <div class="mt-3">
                                                <img
                                                    src="{{ asset('storage/' . $hospital->company_seal) }}"
                                                    alt="Company Seal"
                                                    class="h-20 w-auto object-contain"
                                                >
                                            </div>
                                        @endif

                                        <x-input-error
                                            class="mt-2"
                                            :messages="$errors->get('company_seal')"
                                        />
                                    </div>

                                </div>

                            </div>

                            {{-- Save --}}
                            <div class="flex items-center gap-4">

                                <x-primary-button>
                                    {{ __('Save') }}
                                </x-primary-button>

                                @if (session('status') === 'hospital-updated')
                                    <p
                                        x-data="{ show: true }"
                                        x-show="show"
                                        x-transition
                                        x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-gray-600"
                                    >
                                        {{ __('Saved.') }}
                                    </p>
                                @endif

                            </div>

                        </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>