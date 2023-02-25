<div class="mt-10 sm:mt-0">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Lokáció módosítása - {{ $location->name }}</h3>
                <p class="mt-1 text-sm text-gray-600">Kérem adja meg a lokáció rögzítéséhez szükséges adatokat</p>
                <p class="mt-4 mb-1 text-sm text-gray-600"><strong>Kötelezően kitöltendő mezők:</strong></p>
                <ul class="text-sm pl-4 text-gray-600 list-disc">
                    <li>Lokáció neve</li>
                </ul>
                <p class="mt-2 text-sm text-gray-600">*A kapcsolódó zónák mező kitöltése nem kötelező. Ebben a mezőben egyszerre több zóna is kiválasztható.</p>
            </div>
        </div>
        <div class="mt-5 md:col-span-2 md:mt-0">

            <form method="POST" wire:submit.prevent="update">

                @csrf

                <div class="overflow-hidden shadow sm:rounded-md">
                    <div class="bg-white px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-6 gap-6">

                            <div class="col-span-6 sm:col-span-3 lg:col-span-6" wire:key="locationName">
                                <label for="locationName" class="block text-sm font-medium text-gray-700">Lokáció neve*</label>
                                <input type="text"
                                       name="locationName"
                                       id="locationName"
                                       wire:model="location.name"
                                       class="mt-1 block rounded-md border-gray-300 shadow-sm
                                              focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <x-input-error :messages="$errors->get('location.name')" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-3" wire:key="zoneIDs">
                                <label for="zoneIDs" class="block text-sm font-medium text-gray-700">Kapcsolódó zónák</label>
                                <select id="zoneIDs"
                                        name="zoneIDs"
                                        wire:model="zoneIDs"
                                        multiple
                                        size="5"
                                        class="mt-1 block w-full rounded-md
                                               border border-gray-300 bg-white
                                               py-2 px-3 shadow-sm
                                               focus:border-indigo-500 focus:outline-none focus:ring-indigo-500
                                               sm:text-sm">
                                    <option value="0" disabled wire:key="key-zone-id">Kérem válasszon!</option>
                                    @forelse($zones as $zone)
                                        <option wire:key="key-zone-id-{{ $loop->index }}" value="{{ $zone->id }}"
                                                @foreach($zoneIDs as $selected_id)
                                                    @if ($selected_id === $zone->id)
                                                        selected
                                            @endif
                                            @endforeach
                                        >
                                            {{ $zone->name }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                                <x-input-error :messages="$errors->get('zoneIDs')" class="mt-2" />
                            </div>

                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                        <x-primary-button>{{ __('Módosítás') }}</x-primary-button>
                        <x-secondary-button class="ml-2"><a href="{{ route('locations.index') }}">{{ __('Mégsem') }}</a></x-secondary-button>
                    </div>
                </div>

            </form>

            <x-flash/>

        </div>
    </div>
</div>

