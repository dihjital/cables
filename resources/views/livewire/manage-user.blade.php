<div>

    <div class="flex flex-row flow-root">
        <div class="md:flex md:items-center">
            <div class="md:w-1/12">
                <x-forms.label name="pageSize" label="Page Size:" />
            </div>
            <div class="md:w-1/12">
                <x-forms.select name="pageSize" wire:model="pageSize">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </x-forms.select>
            </div>
        </div>
    </div>

    <x-table>
        <x-slot name="head">
            <x-table.heading class="w-1/12">
                <input type="checkbox" wire:model="selectPage" />
            </x-table.heading>
            <x-table.heading class="w-1/12"></x-table.heading>
            <x-table.heading class="w-2/12">
                <div class="flex items-center">
                    <button wire:click="sortBy('name')"
                            class="leading-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Név
                    </button>
                    <x-table.sort-icon field="name"
                                       :sortField="$sortField" :sortDirection="$sortDirection"></x-table.sort-icon>
                </div>
            </x-table.heading>
            <x-table.heading class="w-2/12">Engedélyezett?</x-table.heading>
            <x-table.heading class="w-2/12">Admin??</x-table.heading>
            <x-table.heading class="w-4/12">
                <div class="flex items-center">
                    <button wire:click="sortBy('updated_at')"
                            class="leading-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Utolsó módosítása
                    </button>
                    <x-table.sort-icon field="updated_at"
                                       :sortField="$sortField" :sortDirection="$sortDirection"></x-table.sort-icon>
                </div>
            </x-table.heading>
        </x-slot>
        <x-slot name="body">
            @if ($selectPage)
                <x-table.row class="bg-gray-200" wire:key="row-message">
                    <x-table.cell colspan="9" class="bt-0">
                        @unless($selectAll)
                            <div class="text-sm">
                                <span>Kiválasztott rekordok száma: <strong>{{ $cables->count() }}</strong> db., ki szeretnéd mind a(z) <strong>{{ $cables->total() }}</strong> db.-ot választani?</span>
                                <a href="#" wire:click="$set('selectAll', true)" class="ml-1 text-blue-600">Összes rekord kiválasztása</a>
                            </div>
                        @else
                            <span class="text-sm">Jelenleg mind a(z) <strong>{{ $cables->total() }}</strong> rekordot kiválasztottad.</span>
                        @endif
                    </x-table.cell>
                </x-table.row>
            @endif
            @forelse($users as $user)
                <x-table.row wire:loading.class.delay="opacity-75" wire:key="row-{{ $user->id }}">
                    <x-table.cell>
                        <input type="checkbox" wire:model="selectedItems" name="selectedItems" value="{{ $user->id }}" />
                    </x-table.cell>
                    <x-table.cell>
                        <img class="w-10 h-10 p-1 rounded-full ring-2 ring-gray-300 dark:ring-gray-500" src="{{ $user->avatarUrl() }}" alt="Profil fotó - {{ $user->name }}">
                    </x-table.cell>
                    <x-table.cell>
                        <div class="text-sm font-medium text-gray-900">
                            {{ $user->name }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $user->email }}
                        </div>
                    </x-table.cell>
                    <x-table.cell>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:click="enableUser({{ $user->id }})" value="" class="sr-only peer" {{ $user->enabled ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300"></span>
                        </label>
                    </x-table.cell>
                    <x-table.cell>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:click="setAdminPrivileges({{ $user->id }})" value="" class="sr-only peer" {{ $user->isAdmin ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300"></span>
                        </label>
                    </x-table.cell>
                    <x-table.cell>
                        {{ \Carbon\Carbon::parse($user->updated_at)->diffForhumans() }}
                    </x-table.cell>
                </x-table.row>
            @empty
                <x-table.row wire:key="row-empty">
                    <x-table.cell colspan="9">
                        <div class="flex justify-center items-center">
                            <span class="py-8 text-base font-medium text-gray-400 uppercase">Nincsen ilyen felhasználó a rendszerben ...</span>
                        </div>
                    </x-table.cell>
                </x-table.row>
            @endforelse
        </x-slot>
    </x-table>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

</div>
