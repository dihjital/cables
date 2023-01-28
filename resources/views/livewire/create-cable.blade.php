<div class="mt-10 sm:mt-0">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Personal Information</h3>
                <p class="mt-1 text-sm text-gray-600">Use a permanent address where you can receive mail.</p>
            </div>
        </div>
        <div class="mt-5 md:col-span-2 md:mt-0">
            <form action="#" method="POST">
                <div class="overflow-hidden shadow sm:rounded-md">
                    <div class="bg-white px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-6 gap-6">

                            <div class="col-span-6 sm:col-span-3" wire:key="startCDOwner">
                                <label for="startCDOwner" class="block text-sm font-medium text-gray-700">Kezdő kapcsolati eszköz tulajdonosa</label>
                                <select id="startCDOwner"
                                        name="startCDOwner"
                                        wire:model="selectCD.startCDOwner"
                                        class="mt-1 block w-full rounded-md
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

                            <div class="col-span-6 sm:col-span-3" wire:key="endCDOwner">
                                <label for="endCDOwner" class="block text-sm font-medium text-gray-700">Végződő kapcsolati eszköz tulajdonosa</label>
                                <select id="endCDOwner"
                                        name="endCDOwner"
                                        wire:model="selectCD.endCDOwner"
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
                            </div>

                            <div class="col-span-6 sm:col-span-3" wire:key="startCDList>
                                <label for="startCDList" class="block text-sm font-medium text-gray-700">Kezdő kapcsolati eszköz</label>
                                <select id="startCDList"
                                        name="startCDList"
                                        wire:model="selectCD.startCDId"
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
                            </div>

                            <div class="col-span-6 sm:col-span-3" wire:key="endCDList">
                                <label for="endCDList" class="block text-sm font-medium text-gray-700">Végződő kapcsolati eszköz</label>
                                <select id="endCDList"
                                        name="endCDList"
                                        wire:model="selectCD.endCDId"
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
                            </div>

                            <div class="col-span-6 sm:col-span-3" wire:key="startCablePairsList">
                                <label for="startCablePairsList" class="block text-sm font-medium text-gray-700">Kezdő eszköz kapcsolati pontjai</label>
                                <select id="startCablePairsList"
                                        name="startCablePairsList"
                                        wire:model="selectCD.startConnectionPointId"
                                        class="mt-1 block w-full rounded-md
                                               border border-gray-300 bg-white
                                               py-2 px-3 shadow-sm
                                               focus:border-indigo-500 focus:outline-none focus:ring-indigo-500
                                               sm:text-sm">
                                    @forelse($startCablePairsList as $cablePair)
                                    <option wire:key="key-startCablePairsList-{{ $loop->index }}"
                                            value="{{ $loop->index }}"
                                            @if ($selectCD['startConnectionPointId'] === $loop->index && $cablePair->status === 'Free')
                                                selected
                                            @endif
                                            @if ($cablePair->status !== 'Free')
                                                disabled
                                            @endif
                                    >
                                        {{ $cablePair->conn_point }} : {{ $cablePair->status }}
                                    </option>
                                    @empty
                                    <option value="0" disabled>Kérem válasszon</option>
                                    @endforelse
                                </select>
                            </div>

                            <div class="col-span-6 sm:col-span-3" wire:key="endCablePairsList">
                                <label for="endCablePairsList" class="block text-sm font-medium text-gray-700">Végződő eszköz kapcsolati pontjai</label>
                                <select id="endCablePairsList"
                                        name="endCablePairsList"
                                        wire:model="selectCD.endConnectionPointId"
                                        class="mt-1 block w-full rounded-md
                                                   border border-gray-300 bg-white
                                                   py-2 px-3 shadow-sm
                                                   focus:border-indigo-500 focus:outline-none focus:ring-indigo-500
                                                   sm:text-sm">
                                    @forelse($endCablePairsList as $cablePair)
                                    <option wire:key="key-endCablePairsList-{{ $loop->index }}"
                                            value="{{ $loop->index }}"
                                            @if ($selectCD['endConnectionPointId'] === $loop->index && $cablePair->status === 'Free')
                                                selected
                                            @endif
                                            @if ($cablePair->status !== 'Free')
                                                disabled
                                            @endif
                                    >
                                        {{ $cablePair->conn_point }} : {{ $cablePair->status }}
                                    </option>
                                    @empty
                                    <option value="0" disabled>Kérem válasszon</option>
                                    @endforelse
                                </select>
                            </div>

                            <div class="col-span-6"><hr></div>

                            <div class="col-span-6 sm:col-span-3" wire:key="cableType">
                                <label for="cableType" class="block text-sm font-medium text-gray-700">Kábel típusa</label>
                                <select id="cableType"
                                        name="cableType"
                                        wire:model="cableTypeId"
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
                            </div>

                            <div class="col-span-6 sm:col-span-3" wire:key="cablePurpose">
                                <label for="cablePurpose" class="block text-sm font-medium text-gray-700">Kábel felhasználási módja</label>
                                <select id="cablePurpose"
                                        name="cablePurpose"
                                        wire:model="cablePurposeId"
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
                            </div>

                            <div class="col-span-6 sm:col-span-3" wire:key="cablePairStatus">
                                <label for="cablePairStatus" class="block text-sm font-medium text-gray-700">Kábelpár státusza</label>
                                <select id="cablePairStatus"
                                        name="cablePairStatus"
                                        wire:model="cablePairStatusId"
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
                            </div>

                            <div class="col-span-6 sm:col-span-3" wire:key="cableName">
                                <label for="cableName" class="block text-sm font-medium text-gray-700">Kábel neve</label>
                                <input type="text"
                                       name="cableName"
                                       id="cableName"
                                       wire:model="cableName"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                       focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
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
                        <x-primary-button>{{ __('Rögzítés') }}</x-primary-button>
                        <x-secondary-button class="ml-2" onclick="{{ route('cables.index') }}">{{ __('Mégsem') }}</x-secondary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
