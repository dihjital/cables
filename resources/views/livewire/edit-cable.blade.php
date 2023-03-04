<div class="mt-10 sm:mt-0">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Kábel módosítása - {{ $cable->full_name }}</h3>
                <p class="mt-1 text-sm text-gray-600">Kérem adja meg a kábel módosításához szükséges adatokat</p>
                <p class="mt-4 mb-1 text-sm text-gray-600"><strong>Kötelezően kitöltendő mezők:</strong></p>
                <ul class="text-sm pl-4 text-gray-600 list-disc">
                    <li>Kezdő kapcsolati eszköz tulajdonosa</li>
                    <li>Végződő kapcsolati eszköz tulajdonosa</li>
                    <li>VKezdő kapcsolati eszköz</li>
                    <li>Végződő kapcsolati eszköz</li>
                    <li>Kábel típusa</li>
                    <li>Kábel felhasználási módja</li>
                    <li>Kábel neve</li>
                    <li>Kábelpár státusza</li>
                </ul>
                <p class="mt-2 text-sm text-gray-600">*A kezdő illetve végződő kapcsolati pontok kitöltése nem kötelező. A kábelpár státusza alapján kell ezeket megadni</p>
            </div>
        </div>
        <div class="mt-5 md:col-span-2 md:mt-0">

            <form method="POST" wire:submit.prevent="update">

                @csrf

                <div class="overflow-hidden shadow sm:rounded-md">
                    <div class="bg-white px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-6 gap-6">

                            <div class="col-span-6 sm:col-span-3" wire:key="startCDOwner">
                                <label for="selectCD[startCDOwner]" class="block text-sm font-medium text-gray-700">Kezdő kapcsolati eszköz tulajdonosa*</label>
                                <div class="flex">
                                    <livewire:create-owner />
                                    <select id="selectCD[startCDOwner]"
                                            name="selectCD[startCDOwner]"
                                            wire:model="selectCD.startCDOwner"
                                            required
                                            class="mt-1 block w-full rounded-none rounded-r-lg
                                                   block flex-1 min-w-0
                                                   border border-gray-300 bg-white
                                                   py-2 px-3 shadow-sm
                                                   focus:border-indigo-500 focus:outline-none focus:ring-indigo-500
                                                   sm:text-sm">
                                            <option value="0" disabled>Kérem válasszon</option>
                                        @forelse($owners as $owner)
                                            <option value="{{ $owner->id }}"
                                                    @if ($selectCD['startCDOwner'] === $owner->id)
                                                    selected
                                                    @endif
                                            >
                                                {{ $owner->name }}
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                                <x-input-error :messages="$errors->get('selectCD.startCDOwner')" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-3" wire:key="endCDOwner">
                                <label for="selectCD[endCDOwner]" class="block text-sm font-medium text-gray-700">Végződő kapcsolati eszköz tulajdonosa*</label>
                                <select id="selectCD[endCDOwner]"
                                        name="selectCD[endCDOwner]"
                                        wire:model="selectCD.endCDOwner"
                                        required
                                        class="mt-1 block w-full rounded-md
                                               border border-gray-300 bg-white
                                               py-2 px-3 shadow-sm
                                               focus:border-indigo-500 focus:outline-none focus:ring-indigo-500
                                               sm:text-sm">
                                        <option value="0" disabled>Kérem válasszon</option>
                                    @forelse($owners as $owner)
                                        <option value="{{ $owner->id }}"
                                                @if ($selectCD['endCDOwner'] === $owner->id)
                                                    selected
                                            @endif
                                        >
                                            {{ $owner->name }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                                <x-input-error :messages="$errors->get('selectCD.endCDOwner')" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-3" wire:key="startCDList">
                                <label for="selectCD[startCDId]" class="block text-sm font-medium text-gray-700">Kezdő kapcsolati eszköz*</label>
                                <select id="selectCD[startCDId]]"
                                        name="selectCD[startCDId]"
                                        wire:model="selectCD.startCDId"
                                        required
                                        class="mt-1 block w-full rounded-md
                                               border border-gray-300 bg-white
                                               py-2 px-3 shadow-sm
                                               focus:border-indigo-500 focus:outline-none focus:ring-indigo-500
                                               sm:text-sm">
                                    <option value="0" disabled>Kérem válasszon</option>
                                    @forelse($startCDList as $startCD)
                                        <option value="{{ $startCD->id }}"
                                                @if ($selectCD['startCDId'] === $startCD->id)
                                                    selected
                                                @endif
                                        >
                                            {{ $startCD->full_name }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                                <x-input-error :messages="$errors->get('selectCD.startCDId')" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-3" wire:key="endCDList">
                                <label for="selectCD[endCDId]" class="block text-sm font-medium text-gray-700">Végződő kapcsolati eszköz*</label>
                                <select id="selectCD[endCDId]"
                                        name="selectCD[endCDId]"
                                        wire:model="selectCD.endCDId"
                                        required
                                        class="mt-1 block w-full rounded-md
                                               border border-gray-300 bg-white
                                               py-2 px-3 shadow-sm
                                               focus:border-indigo-500 focus:outline-none focus:ring-indigo-500
                                               sm:text-sm">
                                    <option value="0" disabled>Kérem válasszon</option>
                                    @forelse($endCDList as $endCD)
                                        <option value="{{ $endCD->id }}"
                                                @if ($selectCD['endCDId'] === $endCD->id)
                                                    selected
                                                @endif
                                        >
                                            {{ $endCD->full_name }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                                <x-input-error :messages="$errors->get('selectCD.endCDId')" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-3" wire:key="startCablePairsList">
                                <label for="selectCD[startConnectionPoint]" class="block text-sm font-medium text-gray-700">Kezdő kapcsolati pont</label>
                                <div class="flex">
                                    <button type="button" wire:click="resetStartConnectionPoint()"
                                            class="mt-1 inline-flex items-center py-2 px-2.5
                                                   bg-red-500 hover:bg-red-600 text-white text-xs rounded-l-md
                                                   border border-r-0 border-red-500">
                                        <i class="fas fa-trash fa-sm" aria-hidden="true" title="Reset"></i>
                                    </button>
                                    <select id="selectCD[startConnectionPoint]"
                                            name="selectCD[startConnectionPoint]"
                                            wire:model="selectCD.startConnectionPoint"
                                            class="mt-1 w-full rounded-none rounded-r-lg block flex-1
                                                   border border-gray-300 bg-white
                                                   py-2 px-3 shadow-sm
                                                   focus:border-indigo-500 focus:outline-none focus:ring-indigo-500
                                                   sm:text-sm">
                                        <option value="" disabled>Kérem válasszon</option>
                                        @forelse($startCablePairsList as $cablePair)
                                        <option wire:key="key-startCablePairsList-{{ $loop->index }}"
                                                value="{{ $cablePair->conn_point }}"
                                                @if ($selectCD['startConnectionPoint'] === $cablePair->conn_point || $cablePair->status === 'Free')
                                                    selected
                                                @endif
                                                @if ($cablePair->status !== 'Free')
                                                    disabled
                                                @endif
                                        >
                                            {{ $cablePair->conn_point }} <small>{{ $cablePair->status }}</small>
                                        </option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                                <x-input-error :messages="$errors->get('selectCD.startConnectionPoint')" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-3" wire:key="endCablePairsList">
                                <label for="selectCD[endConnectionPoint]" class="block text-sm font-medium text-gray-700">Végződő kapcsolati pont</label>
                                <div class="flex">
                                    <button type="button" wire:click="resetEndConnectionPoint()"
                                            class="mt-1 inline-flex items-center py-2 px-2.5
                                                   bg-red-500 hover:bg-red-600 text-white text-xs rounded-l-md
                                                   border border-r-0 border-red-500">
                                        <i class="fas fa-trash fa-sm" aria-hidden="true" title="Reset"></i>
                                    </button>
                                    <select id="selectCD[endConnectionPoint]"
                                            name="selectCD[endConnectionPoint]"
                                            wire:model="selectCD.endConnectionPoint"
                                            class="mt-1 w-full rounded-none rounded-r-lg block flex-1
                                                   border border-gray-300 bg-white
                                                   py-2 px-3 shadow-sm
                                                   focus:border-indigo-500 focus:outline-none focus:ring-indigo-500
                                                   sm:text-sm">
                                        <option value="" disabled>Kérem válasszon</option>
                                        @forelse($endCablePairsList as $cablePair)
                                        <option wire:key="key-endCablePairsList-{{ $loop->index }}"
                                                value="{{ $cablePair->conn_point }}"
                                                @if ($selectCD['endConnectionPoint'] === $cablePair->conn_point || $cablePair->status === 'Free')
                                                    selected
                                                @endif
                                                @if ($cablePair->status !== 'Free')
                                                    disabled
                                                @endif
                                        >
                                            {{ $cablePair->conn_point }} <sub>{{ $cablePair->status }}</sub>
                                        </option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                                <x-input-error :messages="$errors->get('selectCD.endConnectionPoint')" class="mt-2" />
                            </div>

                            <div class="col-span-6"><hr></div>

                            <div class="col-span-6 sm:col-span-3" wire:key="cableType">
                                <label for="cableType" class="block text-sm font-medium text-gray-700">Kábel típusa*</label>
                                <select id="cableType"
                                        name="cableType"
                                        wire:model="cableTypeId"
                                        required
                                        class="mt-1 block w-full rounded-md
                                                   border border-gray-300 bg-white
                                                   py-2 px-3 shadow-sm
                                                   focus:border-indigo-500 focus:outline-none focus:ring-indigo-500
                                                   sm:text-sm">
                                    <option value="0" disabled>Kérem válasszon</option>
                                    @foreach($cableTypes as $type)
                                        <option value="{{ $type->id }}"
                                                @if ($cableTypeId === $type->id)
                                                    selected
                                            @endif
                                        >
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('cableTypeId')" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-3" wire:key="cablePurpose">
                                <label for="cablePurpose" class="block text-sm font-medium text-gray-700">Kábel felhasználási módja*</label>
                                <select id="cablePurpose"
                                        name="cablePurpose"
                                        wire:model="cablePurposeId"
                                        required
                                        class="mt-1 block w-full rounded-md
                                                       border border-gray-300 bg-white
                                                       py-2 px-3 shadow-sm
                                                       focus:border-indigo-500 focus:outline-none focus:ring-indigo-500
                                                       sm:text-sm">
                                    <option value="0" disabled>Kérem válasszon</option>
                                    @foreach($cablePurposes as $purpose)
                                        <option value="{{ $purpose->id }}"
                                                @if ($cablePurposeId === $purpose->id)
                                                    selected
                                            @endif
                                        >
                                            {{ $purpose->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('cablePurposeId')" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-3" wire:key="cablePairStatus">
                                <label for="cablePairStatus" class="block text-sm font-medium text-gray-700">Kábelpár státusza*</label>
                                <select id="cablePairStatus"
                                        name="cablePairStatus"
                                        wire:model="cablePairStatusId"
                                        required
                                        class="mt-1 block w-full rounded-md
                                               border border-gray-300 bg-white
                                               py-2 px-3 shadow-sm
                                               focus:border-indigo-500 focus:outline-none focus:ring-indigo-500
                                               sm:text-sm">
                                    <option value="0" disabled>Kérem válasszon</option>
                                    @foreach($cablePairStatuses as $status)
                                        <option value="{{ $status->id }}"
                                                @if ($cablePairStatusId === $status->id)
                                                    selected
                                            @endif
                                        >
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('cablePairStatusId')" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-3" wire:key="cableName">
                                <label for="cableName" class="block text-sm font-medium text-gray-700">{{ __('Kábel neve*') }}</label>
                                <div class="flex">
                                    <span class="mt-1 inline-flex items-center px-3 py-2 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md">
                                        {{ \App\Models\CableType::whereKey($cableTypeId)?->get('abbreviation')->first()?->abbreviation ?: '?' }}
                                    </span>
                                    <input type="text"
                                           name="cableName"
                                           id="cableName"
                                           wire:model="cableName"
                                           class="mt-1 rounded-none rounded-r-lg border text-gray-900
                                                  focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full
                                                  text-sm border-gray-300 py-2">
                                </div>
                                <x-input-error :messages="$errors->get('cableName')" class="mt-2" />
                            </div>

                            <div class="col-span-6"><hr></div>

                            <div class="col-span-6 sm:col-span-6 lg:col-span-2" wire:key="installTime">
                                <label for="installTime" class="block text-sm font-medium text-gray-700">Telepítés dátuma</label>
                                <input type="date"
                                       name="installTime"
                                       id="installTime"
                                       wire:model="installTime"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                              focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div class="col-span-6"><hr></div>

                            <div class="col-span-6 sm:col-span-3 lg:col-span-6" wire:key="cableComment">
                                <label for="cableComment" class="block text-sm font-medium text-gray-700">Megjegyzés</label>
                                <textarea id="cableComment"
                                          rows="5"
                                          class="text-left text-gray-700 mt-2 p-2.5 w-full
                                                 rounded-md border border-gray-300 shadow-sm
                                                 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                          placeholder="Megjegyzés hozzáadása ..."
                                          wire:model.defer="cableComment">
                                </textarea>
                            </div>

                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                        <x-primary-button>{{ __('Módosítás') }}</x-primary-button>
                        <x-secondary-button class="ml-2"><a href="{{ route('cables.index') }}">{{ __('Mégsem') }}</a></x-secondary-button>
                    </div>
                </div>

            </form>

            <x-flash/>

        </div>
    </div>
</div>
