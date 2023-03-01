<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kapcsolati eszközök szerkesztése') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mt-10 sm:mt-0">

                        <div class="md:grid md:grid-cols-3 md:gap-6">

                            <div class="md:col-span-1">
                                <div class="px-4 sm:px-0">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900">Kapcsolati eszköz módosítása</h3>
                                    <p class="mt-1 text-sm text-gray-600">Kérem adja meg a módosítani kívánt adatokat</p>
                                    <p class="mt-4 mb-1 text-sm text-gray-600"><strong>Kötelezően kitöltendő mezők:</strong></p>
                                    <ul class="text-sm pl-4 text-gray-600 list-disc">
                                        <li>Kapcsolati eszköz neve</li>
                                        <li>Típus</li>
                                        <li>Kezdő kapcsolati pont</li>
                                        <li>Végződő kapcsolati pont</li>
                                        <li>Zóna neve</li>
                                        <li>Lokáció neeve</li>
                                        <li>Tulajdonos</li>
                                    </ul>
                                    <p class="mt-2 text-sm text-gray-600">*A kezdő illetve végződő kapcsolati pontok kitöltése nem kötelező. A kábelpár státusza alapján kell ezeket megadni</p>
                                </div>
                            </div>

                            <div class="mt-5 md:col-span-2 md:mt-0">

                                <form method="POST" action="{{ route('connectivity_device.update', ['connectivity_device' => $connectivity_device->id]) }}" class="w-full border-gray-200 p-6 rounded-xl bg-white mb-6 ">
                                @csrf
                                @method('PATCH')

                                    <div class="overflow-hidden shadow sm:rounded-md">
                                        <div class="bg-white px-4 py-5 sm:p-6">
                                            <div class="grid grid-cols-6 gap-6">

                                                <div class="col-span-6 sm:col-span-3 lg:col-span-6">
                                                    <header class="flex mb-5 justify-center pb-4 border-b border-gray-200">
                                                        <h2 class="font-semibold text-xl">{{ $connectivity_device->full_name }}</h2>
                                                    </header>
                                                </div>

                                                <!-- Connectivity Device short name //-->
                                                <div class="col-span-6 sm:col-span-3">
                                                    <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Kapcsolati eszköz neve*') }}</label>
                                                    <input type="text"
                                                           name="name"
                                                           id="name"
                                                           x-model="name"
                                                           maxlength="5"
                                                           placeholder="A19"
                                                           required
                                                           value="{{ old('name', $connectivity_device->name) }}"
                                                           class="mt-1 rounded-md border text-gray-900
                                                           focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full
                                                           text-sm border-gray-300 py-2">
                                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                                </div>

                                                <!-- Connectivity Device type //-->
                                                <div class="col-span-6 sm:col-span-3">
                                                    <label for="connectivity_device_type_id" class="block text-sm font-medium text-gray-700">{{ __('Típus*') }}</label>
                                                    <select id="connectivity_device_type_id"
                                                            name="connectivity_device_type_id"
                                                            required
                                                            class="mt-1 block w-full rounded-md
                                                            border border-gray-300 bg-white
                                                            py-2 px-3 shadow-sm
                                                            focus:border-indigo-500 focus:outline-none focus:ring-indigo-500
                                                            sm:text-sm">
                                                        @php
                                                            $types = \App\Models\ConnectivityDeviceType::all();
                                                        @endphp
                                                        <option value="" disabled selected>Kérem válasszon!</option>
                                                        @foreach($types as $type)
                                                            <option value="{{ $type->id }}"
                                                                    @if(old('connectivity_device_type_id', $connectivity_device->connectivity_device_type_id) == $type->id)
                                                                        selected
                                                                @endif>
                                                                {{ $type->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <x-input-error :messages="$errors->get('connectivity_device_type_id')" class="mt-2" />
                                                </div>

                                                <div class="col-span-6"><hr></div>

                                                <!-- Start connection point //-->
                                                <div class="col-span-6 sm:col-span-3">
                                                    <label for="start" class="block text-sm font-medium text-gray-700">{{ __('Kezdő kapcsolati pont*') }}</label>
                                                    <input type="text"
                                                           name="start"
                                                           id="start"
                                                           maxlength="11"
                                                           placeholder="Z152S01P001"
                                                           required
                                                           value="{{ old('start', $connectivity_device->start) }}"
                                                           class="mt-1 rounded-md border text-gray-900
                                                           focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full
                                                           text-sm border-gray-300 py-2">
                                                    <x-input-error :messages="$errors->get('start')" class="mt-2" />
                                                </div>

                                                <!-- End connection point //-->
                                                <div class="col-span-6 sm:col-span-3">
                                                    <label for="end" class="block text-sm font-medium text-gray-700">{{ __('Végződő kapcsolati pont*') }}</label>
                                                    <input type="text"
                                                           name="end"
                                                           id="end"
                                                           maxlength="11"
                                                           placeholder="Z152S03P024"
                                                           required
                                                           value="{{ old('end', $connectivity_device->end) }}"
                                                           class="mt-1 rounded-md border text-gray-900
                                                           focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full
                                                           text-sm border-gray-300 py-2">
                                                    <x-input-error :messages="$errors->get('end')" class="mt-2" />
                                                </div>

                                                <!-- Zone name //-->
                                                <div class="col-span-6 sm:col-span-3">
                                                    <label for="zone_id" class="block text-sm font-medium text-gray-700">{{ __('Zóna neve*') }}</label>
                                                    <livewire:zone-lookup :zone="old('zone_id', $connectivity_device)"/>
                                                    <div x-data="{ zone_id: {{ old('zone_id', $connectivity_device) }} }"
                                                         @zone-updated.window="zone_id = $event.detail.zone_id">
                                                        <input type="hidden" name="zone_id" x-model="zone_id">
                                                    </div>
                                                    <x-input-error :messages="$errors->get('zone_id')" class="mt-2" />
                                                </div>

                                                <!-- Location name //-->
                                                <div class="col-span-6 sm:col-span-3">
                                                    <label for="location_id" class="block text-sm font-medium text-gray-700">{{ __('Lokáció neve*') }}</label>
                                                    <livewire:location-lookup :location="old('location_id', $connectivity_device)"/>
                                                    <div x-data="{ location_id: {{ old('location_id', $connectivity_device) }} }"
                                                         @location-updated.window="location_id = $event.detail.location_id">
                                                        <input type="hidden" name="location_id" x-model="location_id">
                                                    </div>
                                                    <x-input-error :messages="$errors->get('location_id')" class="mt-2" />
                                                </div>

                                                <div class="col-span-6"><hr></div>

                                                <!-- Connectivity Device owner //-->
                                                <div class="col-span-6 sm:col-span-3">
                                                    <label for="owner_id" class="block text-sm font-medium text-gray-700">{{ __('Tulajdonos*') }}</label>
                                                    <div class="flex">
                                                        <livewire:create-owner />
                                                        <select id="owner_id"
                                                                name="owner_id"
                                                                required
                                                                class="mt-1 block w-full rounded-none rounded-r-lg
                                                                block flex-1 min-w-0
                                                                border border-gray-300 bg-white
                                                                py-2 px-3 shadow-sm
                                                                focus:border-indigo-500 focus:outline-none focus:ring-indigo-500
                                                                sm:text-sm">
                                                            @php
                                                                $owners = \App\Models\Owner::query()->orderBy('name')->get();
                                                            @endphp
                                                            <option value="" disabled selected>Kérem válasszon!</option>
                                                            @foreach($owners as $owner)
                                                                <option value="{{ $owner->id }}"
                                                                        @if(old('owner_id', $connectivity_device->owner_id) == $owner->id)
                                                                            selected
                                                                    @endif>
                                                                    {{ $owner->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <x-input-error :messages="$errors->get('owner_id')" class="mt-2" />
                                                </div>

                                            </div>
                                        </div>

                                        <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                                            <x-primary-button>{{ __('Módosítás') }}</x-primary-button>
                                            <x-secondary-button class="ml-2"><a href="{{ route('connectivity_device.index') }}">{{ __('Mégsem') }}</a></x-secondary-button>
                                        </div>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <x-flash />

</x-app-layout>
