<div>

    @if ($renderSelect)
        <div>
            <button type="button" wire:click="toggleShowOwnerModal"
                class="mt-1 inline-flex items-center py-5 px-2.5
                       bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-l-md
                       border border-r-0 border-blue-500">
                <i class="fas fa-plus fa-sm" aria-hidden="true" title="{{ __('Add new owner') }}"></i>
            </button>
        </div>
    @else
        <button type="button" wire:click="toggleShowOwnerModal"
           class="fixed z-100 bottom-10 right-8 bg-blue-600 w-20 h-20 rounded-full drop-shadow-lg flex justify-center items-center text-white text-4xl hover:bg-blue-700 hover:drop-shadow-2xl hover:animate-bounce duration-300">
            <svg width="50" height="50" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
            </svg>
        </button>
    @endif

    <form method="POST" wire:submit.prevent="save" id="saveNewOwner">

        @csrf

        <x-modals.dialog wire:model.defer="showOwnerModal">

            <x-slot name="title">
                @if($owner->id)
                    {{ __('Modify Owner') }}
                @else
                    {{ __('Register new owner in the system') }}
                @endif
            </x-slot>

            <x-slot name="content">

                <p class="col-span-6 sm:col-span-3 lg:col-span-6 text-gray-700 dark:text-gray-200 mb-4">
                    {{ __('Please provide the name of the owner. It must be at least 2 characters long and should be unique in the database.') }}
                </p>

                <div class="col-span-6 sm:col-span-3 col-span-6 mt-4 mb-4">
                    <label for="ownerName" class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Owner\'s name') }}:</label>
                    <input  id="ownerName"
                            name="ownerName"
                            wire:model.defer="owner.name"
                            type="text"
                            class="mt-1 rounded-none rounded-r-lg border text-gray-900
                                   focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full
                                   text-sm border-gray-300 py-2">
                    <x-input-error :messages="$errors->get('owner.name')" class="mt-2" />
                </div>

            </x-slot>

            <x-slot name="footer">
                <x-primary-button wire:click="save" form="saveNewOwner">{{ $owner->id ? __('Modify') : __('Save') }}</x-primary-button>
                <x-secondary-button wire:click.self="toggleShowOwnerModal" class="ml-2">{{ __('Cancel') }}</x-secondary-button>
            </x-slot>

        </x-modals.dialog>

    </form>

</div>
