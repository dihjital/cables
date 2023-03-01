<div>

    <div>
        <button type="button" wire:click="toggleShowOwnerModal"
            class="mt-1 inline-flex items-center py-5 px-2.5
                   bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-l-md
                   border border-r-0 border-blue-500">
            <i class="fas fa-plus fa-sm" aria-hidden="true" title="Add new owner"></i>
        </button>
    </div>

    <form method="POST" wire:submit.prevent="save" id="saveNewOwner">

        @csrf

        <x-modals.dialog wire:model.defer="showOwnerModal">

            <x-slot name="title">Új tulajdonos rögzítése a rendszerben</x-slot>

            <x-slot name="content">

                <p class="col-span-6 sm:col-span-3 lg:col-span-6 text-gray-700 dark:text-gray-200 mb-4">
                    A kiválasztott kábelek tömeges módosítása esetén a kábelpár státusza,
                    illetve a kábelek felhasználási módja változtatható.
                </p>

                <div class="col-span-6 sm:col-span-3 col-span-6 mt-4 mb-4">
                    <label for="ownerName" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tulajdonos neve:</label>
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
                <x-primary-button wire:click="save" form="saveNewOwner">{{ __('Rögzítés') }}</x-primary-button>
                <x-secondary-button wire:click.self="toggleShowOwnerModal" class="ml-2">{{ __('Mégsem') }}</x-secondary-button>
            </x-slot>

        </x-modals.dialog>

    </form>

</div>
