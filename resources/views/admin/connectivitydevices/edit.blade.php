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

                    <form method="POST" action="{{ route('connectivity_device.update', ['connectivity_device' => $connectivity_device->id]) }}" class="w-full border-gray-200 p-6 rounded-xl bg-white mb-6 ">
                        @csrf
                        @method('PATCH')
                        <header class="flex mb-5 justify-center pb-4 border-b border-gray-200">
                            <h2 class="font-semibold text-xl">{{ $connectivity_device->full_name }}</h2>
                        </header>
                        <input type="hidden" name="url" value="{{ url()->previous() }}" />
                        <x-forms.input name="name"
                                       label="Kapcsolati eszköz neve"
                                       maxlength="5"
                                       placeholder="A19"
                                       required
                                       :value="old('name', $connectivity_device->name)"></x-forms.input>
                        <div class="md:flex md:items-center mb-6">
                            <x-forms.label name="connectivity_device_type_id" label="Típus"></x-forms.label>
                            <div class="md:w-1/3">
                                <x-forms.select name="connectivity_device_type_id" required>
                                    @php
                                        $types = \App\Models\ConnectivityDeviceType::all();
                                    @endphp
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}"
                                                @if(old('connectivity_device_type_id', $connectivity_device->connectivity_device_type_id) == $type->id)
                                                    selected
                                            @endif>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </x-forms.select>
                            </div>
                        </div>
                        <x-forms.input name="start"
                                       label="Kezdő kapcsolati pont"
                                       maxlength="11"
                                       placeholder="Z152S01P001"
                                       required
                                      :value="old('start', $connectivity_device->start)"></x-forms.input>
                        <x-forms.input name="end"
                                       label="Végső kapcsolati pont"
                                       maxlength="11"
                                       placeholder="Z152S03P024"
                                       required
                                       :value="old('end', $connectivity_device->end)"></x-forms.input>
                        <div class="md:flex md:items-center mb-6">
                            <x-forms.label name="zone_id" label="Zóna neve"></x-forms.label>
                            <div class="md:w-1/6">
                                <livewire:zone-lookup :zone="old('zone_id', $connectivity_device)"/>
                                <div x-data="{ zone_id: {{ old('zone_id', $connectivity_device) }} }"
                                     @zone-updated.window="zone_id = $event.detail.zone_id">
                                    <input type="hidden" name="zone_id" x-model="zone_id">
                                </div>
                                @error('zone_id')
                                <span class="inline-flex text-sm text-red-700">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="md:flex md:items-center mb-6">
                            <x-forms.label name="location_id" label="Lokáció neve"></x-forms.label>
                            <div class="md: w-1/6">
                                <livewire:location-lookup :location="old('location_id', $connectivity_device)"/>
                                <div x-data="{ location_id: {{ old('location_id', $connectivity_device) }} }"
                                     @location-updated.window="location_id = $event.detail.location_id">
                                    <input type="hidden" name="location_id" x-model="location_id">
                                </div>
                                @error('location_id')
                                <span class="inline-flex text-sm text-red-700">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="md:flex md:items-center mb-6">
                            <x-forms.label name="owner_id" label="Tulajdonos"></x-forms.label>
                            <div class="md:w-1/3">
                                <x-forms.select name="owner_id" required>
                                    @php
                                        $owners = \App\Models\Owner::all();
                                    @endphp
                                    @foreach($owners as $owner)
                                        <option value="{{ $owner->id }}"
                                                @if(old('owner_id', $connectivity_device->owner_id) == $owner->id)
                                                    selected
                                            @endif>
                                            {{ $owner->name }}
                                        </option>
                                    @endforeach
                                </x-forms.select>
                            </div>
                        </div>
                        <div class="flex justify-end mt-5 border-t border-gray-200 pt-6">
                            <button type="submit" class="bg-blue-500 text-white uppercase font-semibold text-xs py-2 px-10 rounded-2xl hover:bg-blue-600">
                                Módosítás
                            </button>
                            <a href="{{ url()->previous() }}" class="bg-red-500 text-white uppercase font-semibold text-xs ml-2 py-2 px-10 rounded-2xl hover:bg-red-600">
                                Mégsem
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <x-flash />

</x-app-layout>
