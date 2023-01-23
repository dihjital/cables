@props(['action'])

<form x-data="{ name: '{{ old('name') }}', zone: '', location: '' }"
      method="POST" action="{{ $action }}" class="w-full border-gray-200 p-6 rounded-xl bg-white mb-6 ">
    @csrf
    <header class="flex mb-5 justify-center pb-4 border-b border-gray-200">
        <h2 class="font-semibold text-xl inline-flex">
            Kapcsolati eszköz teljes neve:
            <p x-text="zone" class="ml-2"></p>/
            <p x-text="location"></p>-
            <p x-text="name"></p>
        </h2>
    </header>
    <x-forms.input name="name"
                   x-model="name"
                   label="Kapcsolati eszköz neve"
                   maxlength="5"
                   placeholder="A19"
                   required></x-forms.input>
    <div class="md:flex md:items-center mb-6">
        <x-forms.label name="connectivity_device_type_id" label="Típus"></x-forms.label>
        <div class="md:w-1/3">
            <x-forms.select name="connectivity_device_type_id" required>
                @php
                    $types = \App\Models\ConnectivityDeviceType::all();
                @endphp
                    <option value="" disabled selected>Kérem válasszon!</option>
                @foreach($types as $type)
                    <option value="{{ $type->id }}"
                            @if(old('connectivity_device_type_id') == $type->id)
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
                   required></x-forms.input>
    <x-forms.input name="end"
                   label="Végső kapcsolati pont"
                   maxlength="11"
                   placeholder="Z152S03P024"
                   required></x-forms.input>
    <div class="md:flex md:items-center mb-6">
        <x-forms.label name="zone_id" label="Zóna neve"></x-forms.label>
        <div class="md:w-1/12">
            <x-forms.select name="zone_id" @change="zone = $event.target[$event.target.selectedIndex].text" required>
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
            </x-forms.select>
        </div>
    </div>
    <div class="md:flex md:items-center mb-6">
        <x-forms.label name="location_id" label="Lokáció neve"></x-forms.label>
        <div class="md:w-1/12">
            <x-forms.select name="location_id" @change="location = $event.target[$event.target.selectedIndex].text" required>
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
            </x-forms.select>
        </div>
    </div>
    <div class="md:flex md:items-center mb-6">
        <x-forms.label name="owner_id" label="Tulajdonos"></x-forms.label>
        <div class="md:w-1/3">
            <x-forms.select name="owner_id" required>
                @php
                    $owners = \App\Models\Owner::query()->orderBy('name')->get();
                @endphp
                <option value="" disabled selected>Kérem válasszon!</option>
                @foreach($owners as $owner)
                    <option value="{{ $owner->id }}"
                            @if(old('owner_id') == $owner->id)
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
            Rögzítés
        </button>
        <a href="{{ url()->previous() }}" class="bg-red-500 text-white uppercase font-semibold text-xs ml-2 py-2 px-10 rounded-2xl hover:bg-red-600">
            Mégsem
        </a>
    </div>
</form>
