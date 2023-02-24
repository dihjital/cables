<div class="mt-10 sm:mt-0">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Zóna módosítása - {{ $zone->name }}</h3>
                <p class="mt-1 text-sm text-gray-600">Kérem adja meg a zóna rögzítéséhez szükséges adatokat</p>
                <p class="mt-4 mb-1 text-sm text-gray-600"><strong>Kötelezően kitöltendő mezők:</strong></p>
                <ul class="text-sm pl-4 text-gray-600 list-disc">
                    <li>Zóna neve</li>
                </ul>
                <p class="mt-2 text-sm text-gray-600">*A kapcsolódó lokációk mező kitöltése nem kötelező. Ebben a mezőben egyszerre több lokáció is kiválasztható.</p>
            </div>
        </div>
        <div class="mt-5 md:col-span-2 md:mt-0">

            <form method="POST" wire:submit.prevent="update">

                @csrf

                <div class="overflow-hidden shadow sm:rounded-md">
                    <div class="bg-white px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-6 gap-6">

                            <div class="col-span-6 sm:col-span-3 lg:col-span-6" wire:key="zoneName">
                                <label for="zoneName" class="block text-sm font-medium text-gray-700">Zóna neve*</label>
                                <input type="text"
                                       name="zoneName"
                                       id="zoneName"
                                       wire:model="zone.name"
                                       class="mt-1 block rounded-md border-gray-300 shadow-sm
                                              focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <x-input-error :messages="$errors->get('zone.name')" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-3" wire:key="locationIDs">
                                <label for="locationIDs" class="block text-sm font-medium text-gray-700">Kapcsolódó lokációk</label>
                                <select id="locationIDs"
                                        name="locationIDs"
                                        wire:model="locationIDs"
                                        multiple
                                        size="5"
                                        class="mt-1 block w-full rounded-md
                                               border border-gray-300 bg-white
                                               py-2 px-3 shadow-sm
                                               focus:border-indigo-500 focus:outline-none focus:ring-indigo-500
                                               sm:text-sm">
                                    <option value="0" disabled wire:key="key-location-id">Kérem válasszon!</option>
                                    @forelse($locations as $location)
                                        <option wire:key="key-location-id-{{ $loop->index }}" value="{{ $location->id }}"
                                                @foreach($locationIDs as $selected_id)
                                                    @if ($selected_id === $location->id)
                                                        selected
                                            @endif
                                            @endforeach
                                        >
                                            {{ $location->name }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                                <x-input-error :messages="$errors->get('locationIDs')" class="mt-2" />
                            </div>

                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                        <x-primary-button>{{ __('Módosítás') }}</x-primary-button>
                        <x-secondary-button class="ml-2"><a href="{{ route('zones.index') }}">{{ __('Mégsem') }}</a></x-secondary-button>
                    </div>
                </div>

            </form>

            <x-flash/>

        </div>
    </div>
</div>

