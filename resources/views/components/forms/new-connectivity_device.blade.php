@props(['action'])

<div class="mt-10 sm:mt-0">

    <div class="md:grid md:grid-cols-3 md:gap-6">

        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Új kapcsolati eszköz rögzítése</h3>
                <p class="mt-1 text-sm text-gray-600">Kérem adja meg a kapcsolati eszköz rögzítéséhez szükséges adatokat</p>
                <p class="mt-4 mb-1 text-sm text-gray-600"><strong>{{ __('Mandatory fields') }}:</strong></p>
                <ul class="text-sm pl-4 text-gray-600 list-disc">
                    <li>Kapcsolati eszköz neve</li>
                    <li>{{ __('Type') }}</li>
                    <li>{{ __('Start connection point') }}</li>
                    <li>{{ __('End connection point') }}</li>
                    <li>{{ __('Zone name') }}</li>
                    <li>{{ __('Location name') }}</li>
                    <li>{{ __('Owner') }}</li>
                </ul>
                <p class="mt-2 text-sm text-gray-600"></p>
            </div>
        </div>

        <div class="mt-5 md:col-span-2 md:mt-0">

            <form x-data="{ name: '{{ old('name', 'A19') }}', zone: $refs.zone_id[$refs.zone_id.selectedIndex].text, location: $refs.location_id[$refs.location_id.selectedIndex].text }"
                  method="POST" action="{{ $action }}" class="w-full border-gray-200 p-6 rounded-xl bg-white mb-6 ">

                @csrf

                <div class="overflow-hidden shadow sm:rounded-md">
                    <div class="bg-white px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-6 gap-6">

                            <!-- Calculated field for Connectivity Device full name //-->
                            <div class="col-span-6 sm:col-span-3 lg:col-span-6">
                                <header class="flex mb-5 justify-center pb-4 border-b border-gray-200">
                                    <h2 class="font-semibold text-xl inline-flex">
                                        Kapcsolati eszköz teljes neve:
                                        <p x-text="zone" class="ml-2"></p>/
                                        <p x-text="location"></p>-
                                        <p x-text="name"></p>
                                    </h2>
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
                                       value="{{ old('name') }}"
                                       class="mt-1 rounded-none rounded-r-lg border text-gray-900
                                              focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full
                                              text-sm border-gray-300 py-2">
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Connectivity Device type //-->
                            <div class="col-span-6 sm:col-span-3">
                                <label for="connectivity_device_type_id" class="block text-sm font-medium text-gray-700">{{ __('Type') }}*</label>
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
                                        <option value="" disabled selected>{{ __('Please select') }}!</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}"
                                                @if(old('connectivity_device_type_id') == $type->id)
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
                                <label for="start" class="block text-sm font-medium text-gray-700">{{ __('Start connection point') }}*</label>
                                <input type="text"
                                       name="start"
                                       id="start"
                                       maxlength="11"
                                       placeholder="Z152S01P001"
                                       required
                                       value="{{ old('start') }}"
                                       class="mt-1 rounded-none rounded-r-lg border text-gray-900
                                              focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full
                                              text-sm border-gray-300 py-2">
                                <x-input-error :messages="$errors->get('start')" class="mt-2" />
                            </div>

                            <!-- End connection point //-->
                            <div class="col-span-6 sm:col-span-3">
                                <label for="end" class="block text-sm font-medium text-gray-700">{{ __('End connection point') }}*</label>
                                <input type="text"
                                       name="end"
                                       id="end"
                                       maxlength="11"
                                       placeholder="Z152S03P024"
                                       required
                                       value="{{ old('end') }}"
                                       class="mt-1 rounded-none rounded-r-lg border text-gray-900
                                              focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full
                                              text-sm border-gray-300 py-2">
                                <x-input-error :messages="$errors->get('end')" class="mt-2" />
                            </div>

                            <!-- Zone name //-->
                            <div class="col-span-6 sm:col-span-3">
                                <label for="zone_id" class="block text-sm font-medium text-gray-700">{{ __('Zone name') }}*</label>
                                <select id="zone_id"
                                        name="zone_id"
                                        x-ref="zone_id"
                                        required
                                        @change="zone = $event.target[$event.target.selectedIndex].text"
                                        class="mt-1 block w-full rounded-md
                                               border border-gray-300 bg-white
                                               py-2 px-3 shadow-sm
                                               focus:border-indigo-500 focus:outline-none focus:ring-indigo-500
                                               sm:text-sm">
                                    @php
                                        $zones = \App\Models\Zone::all();
                                    @endphp
                                    @foreach($zones as $zone)
                                        <option value="{{ $zone->id }}"
                                                @if(old('zone_id') == $zone->id)
                                                    selected
                                                @endif>
                                            {{ $zone->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('zone_id')" class="mt-2" />
                            </div>

                            <!-- Location name //-->
                            <div class="col-span-6 sm:col-span-3">
                                <label for="location_id" class="block text-sm font-medium text-gray-700">{{ __('Location name') }}*</label>
                                <select id="location_id"
                                        name="location_id"
                                        x-ref="location_id"
                                        required
                                        @change="location = $event.target[$event.target.selectedIndex].text"
                                        class="mt-1 block w-full rounded-md
                                               border border-gray-300 bg-white
                                               py-2 px-3 shadow-sm
                                               focus:border-indigo-500 focus:outline-none focus:ring-indigo-500
                                               sm:text-sm">
                                    @php
                                        $locations = \App\Models\Location::all();
                                    @endphp
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}"
                                                @if(old('location_id') == $location->id)
                                                    selected
                                                @endif>
                                            {{ $location->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('location_id')" class="mt-2" />
                            </div>

                            <div class="col-span-6"><hr></div>

                            <!-- Connectivity Device owner //-->
                            <div class="col-span-6 sm:col-span-3">
                                <label for="owner_id" class="block text-sm font-medium text-gray-700">{{ __('Owner') }}*</label>
                                <select id="owner_id"
                                        name="owner_id"
                                        required
                                        class="mt-1 block w-full rounded-md
                                               border border-gray-300 bg-white
                                               py-2 px-3 shadow-sm
                                               focus:border-indigo-500 focus:outline-none focus:ring-indigo-500
                                               sm:text-sm">
                                    @php
                                        $owners = \App\Models\Owner::query()->orderBy('name')->get();
                                    @endphp
                                    <option value="" disabled selected>{{ __('Please select') }}!</option>
                                    @foreach($owners as $owner)
                                        <option value="{{ $owner->id }}"
                                                @if(old('owner_id') == $owner->id)
                                                    selected
                                                @endif>
                                            {{ $owner->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('owner_id')" class="mt-2" />
                            </div>

                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                        <x-primary-button>{{ __('Rögzítés') }}</x-primary-button>
                        <x-secondary-button class="ml-2"><a href="{{ route('connectivity_device.index') }}">{{ __('Cancel') }}</a></x-secondary-button>
                    </div>

                </div>

            </form>

            <x-flash/>

        </div>
    </div>
</div>
