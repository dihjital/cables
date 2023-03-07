<div>

    <livewire:create-owner :renderSelect="false"/>

    <div class="flex flex-col">

        <div class="flex flex-row items-center justify-center space-x-4 mb-4">
            <div class="relative w-3/12">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <input type="search" wire:model.debounce.500ms="search.owner_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="Keresés tulajdonos nevére ...">
            </div>
        </div>

        <div class="flex flex-row flow-root">
            <div class="flex items-center">
                <div class="w-1/12">
                    <x-forms.label name="pageSize" label="Page Size:" />
                </div>
                <div class="w-1/12 mr-4">
                    <x-forms.select name="pageSize" wire:model="pageSize">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </x-forms.select>
                </div>
                <div class="w-full float-right inline-flex rounded-md justify-end space-x-1">
                    @if(count($selectedItems))
                        <button class="px-5 py-2 bg-gray-600 hover:bg-gray-700 text-white text-xs rounded-xl"
                                wire:click="confirmDelete()"
                        >
                            <i class="fas fa-trash fa-xl mr-2" aria-hidden="true"></i>
                            Delete
                        </button>
                    @endif
                    @if (count(array_filter($search)))
                        <x-buttons.reset-filter wire:click="resetFiltering">
                            Reset
                        </x-buttons.reset-filter>
                    @endif
                </div>
            </div>
        </div>

        <x-table>
            <x-slot name="head">
                <x-table.heading class="w-1">
                    <input type="checkbox" wire:model="selectPage" />
                </x-table.heading>
                <x-table.heading class="w-3/12">
                    <div class="flex items-center">
                        <button wire:click="sortBy('id')"
                                class="leading-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tulajdonos azonosítója
                        </button>
                        <x-table.sort-icon field="id" :sortField="$sortField" :sortDirection="$sortDirection" />
                    </div>
                </x-table.heading>
                <x-table.heading class="w-3/12">
                    <div class="flex items-center">
                        <button wire:click="sortBy('name')"
                                class="leading-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tulajdonos neve
                        </button>
                        <x-table.sort-icon field="name" :sortField="$sortField" :sortDirection="$sortDirection" />
                    </div>
                </x-table.heading>
                <x-table.heading class="w-3/12">
                    <div class="flex items-center">
                        Kapcsolati eszközök száma
                    </div>
                </x-table.heading>
                <x-table.heading class="w-1/12"></x-table.heading>
            </x-slot>
            <x-slot name="body">
                @if ($selectPage)
                    <x-table.row class="bg-gray-200" wire:key="row-message">
                        <x-table.cell colspan="5" class="bt-0">
                            @unless($selectAll)
                                <div class="text-sm">
                                    <span>Kiválasztott rekordok száma: <strong>{{ $owners->count() }}</strong> db., ki szeretnéd mind a(z) <strong>{{ $owners->total() }}</strong> db.-ot választani?</span>
                                    <a href="#" wire:click="$set('selectAll', true)" class="ml-1 text-blue-600">Összes rekord kiválasztása</a>
                                </div>
                            @else
                                <span class="text-sm">Jelenleg mind a(z) <strong>{{ $owners->total() }}</strong> rekordot kiválasztottad.</span>
                            @endif
                        </x-table.cell>
                    </x-table.row>
                @endif
                @forelse($owners as $owner)
                    <x-table.row wire:loading.class.delay="opacity-75" wire:key="row-{{ $owner->id }}">
                        <x-table.cell>
                            <input type="checkbox" wire:model="selectedItems" name="selectedItems" value="{{ $owner->id }}" />
                        </x-table.cell>
                        <x-table.cell>
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $owner->id }}
                                </div>
                            </div>
                        </x-table.cell>
                        <x-table.cell>
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900 truncate">
                                    {{ $owner->name }}
                                </div>
                            </div>
                        </x-table.cell>
                        <x-table.cell>
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900 truncate">
                                    {{ $owner->connectivity_devices()->count() }}
                                </div>
                            </div>
                        </x-table.cell>
                        <x-table.cell class="text-right text-sm font-medium space-x-2">
                            <button type="button" wire:click="toggleNewOwnerModal({{ $owner->id }})" class="px-3 py-3 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-md">
                                <i class="fas fa-edit fa-sm" aria-hidden="true" title="Edit"></i>
                            </button>
                            <button type="button" wire:click="confirmDelete({{ $owner->id }})" class="px-3 py-3 bg-red-500 hover:bg-red-600 text-white text-xs rounded-md">
                                <i class="fas fa-trash fa-sm" aria-hidden="true" title="Delete"></i>
                            </button>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row wire:key="row-empty">
                        <x-table.cell colspan="5">
                            <div class="flex justify-center items-center">
                                <span class="py-8 text-base font-medium text-gray-400 uppercase">Nincsen ilyen tulajdonos ...</span>
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-slot>
        </x-table>

        <div class="mt-5">
            {{ $owners->links() }}
        </div>

    </div>

    <x-flash/>

    <form method="POST" wire:submit.prevent="delete">

        @csrf
        @method('DELETE')

        <x-modals.delete wire:model.defer="showDeleteModal">
            @if ($selectedItems)
                <x-slot name="title">Tulajdonosok törlése</x-slot>
            @else
                <x-slot name="title">Tulajdonos törlése - {{ $currentOwner->name }}</x-slot>
            @endif
            <x-slot name="body">
                @if ($showDeleteModal && $selectedItems )
                    Biztos benne, hogy törölni kívánja a kiválasztott tulajdonosokat? A művelet nem visszavonható
                    változást eredményez a rendszer adatbázisában.
                    <label for="message" class="block mt-2 mb-2 text-sm text-gray-900">
                        A törölni kívánt tulajdonosok listája (első 10 db.):
                    </label>
                    <textarea id="message"
                              rows="4"
                              class="text-left p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                    >@foreach (collect($selectedItems)->take(10) as $item){{ trim($owners->firstWhere('id', $item)->name).PHP_EOL }}@endforeach</textarea>
                @else
                    Biztos benne, hogy törölni kívánja a kiválasztott tulajdonost? A művelet nem visszavonható
                    változást eredményez a rendszer adatbázisában.
                @endif
            </x-slot>
            <x-slot name="footer">
                <x-forms.button
                    type="submit"
                    class="border-transparent bg-red-600 text-white hover:bg-red-700 focus:ring-red-500"
                >
                    Tulajdonos törlése
                </x-forms.button>
                <x-forms.button
                    class="mt-3 border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:ring-indigo-500 sm:mt-0"
                    wire:click="$set('showDeleteModal', false)"
                >
                    Mégsem
                </x-forms.button>
            </x-slot>
        </x-modals.delete>

    </form>

</div>
