<div>

    <div class="flex flex-row flow-root">
        <div class="md:flex md:items-center">
            @include('partials/page-size')
        </div>
    </div>

    <x-table>
        <x-slot name="head">
            <x-table.heading class="w-1/12"></x-table.heading>
            <x-table.heading class="w-4/12">
                <div class="flex items-center">
                    <button wire:click="sortBy('name')"
                            class="leading-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('Name') }}
                    </button>
                    <x-table.sort-icon field="name"
                                       :sortField="$sortField" :sortDirection="$sortDirection"></x-table.sort-icon>
                </div>
            </x-table.heading>
            <x-table.heading class="w-2/12">{{ __('Enabled') }}?</x-table.heading>
            <x-table.heading class="w-2/12">{{ __('Admin') }}?</x-table.heading>
            <x-table.heading class="w-4/12">
                <div class="flex items-center">
                    <button wire:click="sortBy('last_login_at')"
                            class="leading-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('Last login at') }}
                    </button>
                    <x-table.sort-icon field="last_login_at"
                                       :sortField="$sortField" :sortDirection="$sortDirection"></x-table.sort-icon>
                </div>
            </x-table.heading>
        </x-slot>
        <x-slot name="body">
            @forelse($users as $user)
                <x-table.row wire:loading.class.delay="opacity-75" wire:key="row-{{ $user->id }}">
                    <x-table.cell>
                        <img class="w-10 h-10 p-1 rounded-full ring-2 ring-gray-300 dark:ring-gray-500" src="{{ $user->avatarUrl() }}" alt="{{  __('Picture - :Name', ["name" => $user->name]) }}">
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
                        <div class="text-sm font-medium text-gray-900">
                            {{ $user->last_login_at ?? '-' }}
                        </div>
                        <div class="text-sm text-gray-500">
                            @if($user->last_login_at)
                                {{ \Carbon\Carbon::parse($user->last_login_at)->diffForhumans() }}
                            @endif
                        </div>
                    </x-table.cell>
                </x-table.row>
            @empty
                <x-table.row wire:key="row-empty">
                    <x-table.cell colspan="8">
                        <div class="flex justify-center items-center">
                            <span class="py-8 text-base font-medium text-gray-400 uppercase">{{ __('There is no such user in the system') }} ...</span>
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
