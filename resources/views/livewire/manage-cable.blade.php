<div>

    <a href="{{ route('cables.create') }}" title="{{ __('New record') }}"
       class="fixed z-100 bottom-10 right-8 bg-blue-600 w-20 h-20 rounded-full drop-shadow-lg flex justify-center items-center text-white text-4xl hover:bg-blue-700 hover:drop-shadow-2xl hover:animate-bounce duration-300">
        <svg width="50" height="50" fill="currentColor"
             class="bi bi-plus" viewBox="0 0 16 16">
            <path
                d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg>
    </a>

    <div class="flex flex-row flow-root">
        <div class="float-right space-x-1">
            <livewire:import-cables />
            @if(count($selectedItems))
                <x-buttons.export wire:click="exportSelected">
                    {{ __('Export') }}
                </x-buttons.export>
                <button class="px-5 py-2 bg-gray-600 hover:bg-gray-700 text-white text-xs rounded-xl"
                        wire:click="confirmDelete()"
                >
                    <i class="fas fa-trash fa-xl mr-2" aria-hidden="true"></i>
                    {{ __('Delete') }}
                </button>
            @endif
            @if (count(array_filter($search)))
                <x-buttons.reset-filter wire:click="resetFiltering">
                    {{ __('Reset') }}
                </x-buttons.reset-filter>
            @endif
        </div>
    </div>

    <div class="flex flex-row items-center justify-center space-x-4 mt-4 mb-8">
        <div class="relative w-3/12">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <input type="search" wire:model.debounce.500ms="search.full_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="{{ __('Keres??s k??bel nev??re') }} ...">
        </div>
    </div>

    <div class="flex flex-row flow-root">
        <div class="flex items-center">
            @include('partials/page-size')

            @php
                $cablePairStatuses = \App\Models\CablePairStatus::all();
            @endphp

            <div class="w-full float-right inline-flex rounded-md justify-end" role="group">
            @foreach ($cablePairStatuses as $status)
                <button type="button"
                        wire:click="$set('search.status', {{ $status->id }})"
                        class="px-4 py-2 text-sm font-medium text-gray-900 bg-white
                                             {{ $loop->iteration % 2 ? 'border' : 'border-t border-b' }}  border-gray-200
                                             {{ $loop->first ? 'rounded-l-lg' : ($loop->last ? 'rounded-r-md' : '') }}
                                             hover:bg-gray-100 hover:text-blue-700
                                             focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700
                                             dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600
                                             dark:focus:ring-blue-500 dark:focus:text-white">
                    {{ __($status->name) }}
                </button>
            @endforeach
            </div>

        </div>
    </div>

    <x-table>
        <x-slot name="head">
            <x-table.heading class="w-1/12">
                <input type="checkbox" wire:model="selectPage" />
            </x-table.heading>
            <x-table.heading class="w-2/12">
                <div class="flex items-center whitespace-nowrap">
                    <button wire:click="sortBy('name')"
                            class="leading-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('Name') }}
                    </button>
                    <x-table.sort-icon field="name" :sortField="$sortField" :sortDirection="$sortDirection"/>
                </div>
            </x-table.heading>
            <x-table.heading class="w-1/12">{{ __('Start device') }}</x-table.heading>
            <x-table.heading class="w-1/12">{{ __('End device') }}</x-table.heading>
            <x-table.heading class="w-1/12">{{ __('Status') }}</x-table.heading>
            <x-table.heading class="w-1/12">
                <div class="flex items-center whitespace-nowrap">
                    <button wire:click="sortBy('i_time')"
                            class="leading-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('Install date') }}
                    </button>
                    <x-table.sort-icon field="i_time" :sortField="$sortField" :sortDirection="$sortDirection"/>
                </div>
            </x-table.heading>
            <x-table.heading class="w-2/12">{{ __('Purpose') }}</x-table.heading>
            <x-table.heading class="w-2/12"></x-table.heading>
        </x-slot>
        <x-slot name="body">
            @if ($selectPage)
                <x-table.row class="bg-gray-200" wire:key="row-message">
                    <x-table.cell colspan="8" class="bt-0">
                        @unless($selectAll)
                            <div class="text-sm">
                                <span>{{ __('Number of selected records') }}: <strong>{{ $cables->count() }}</strong> db., ki szeretn??d mind a(z) <strong>{{ $cables->total() }}</strong> db.-ot v??lasztani?</span>
                                <a href="#" wire:click="$set('selectAll', true)" class="ml-1 text-blue-600">{{ __('Select all records') }}</a>
                            </div>
                        @else
                            <span class="text-sm">Jelenleg mind a(z) <strong>{{ $cables->total() }}</strong> rekordot kiv??lasztottad.</span>
                        @endif
                    </x-table.cell>
                </x-table.row>
            @endif
            @forelse($cables as $cable)
                <x-table.row wire:loading.class.delay="opacity-75" wire:key="row-{{ $cable->id }}">
                    <x-table.cell>
                        <input type="checkbox" wire:model="selectedItems" name="selectedItems" value="{{ $cable->id }}" />
                    </x-table.cell>
                    <x-table.cell>
                        <div class="text-sm font-medium text-gray-900">
                            {{ $cable->full_name }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $cable->cable_type->name }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $cable->owner->name }}
                        </div>
                    </x-table.cell>
                    <x-table.cell>
                        <div class="text-sm font-medium text-gray-900">
                            {{ $cable->cd_start->full_name }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $cable->cd_start->owner->name }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $cable->start_point }}
                        </div>
                    </x-table.cell>
                    <x-table.cell>
                        <div class="text-sm font-medium text-gray-900">
                            {{ $cable->cd_end->full_name }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $cable->cd_end->owner->name }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $cable->end_point }}
                        </div>
                    </x-table.cell>
                    <x-table.cell>
                        <div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">
                                <span style="{{ $cable->status_color }}"
                                      class="text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">
                                    {{ $cable->status }}
                                </span>
                            </div>
                        </div>
                    </x-table.cell>
                    <x-table.cell>
                        <div class="text-sm font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($cable->i_time)->diffForhumans() }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $cable->date_for_humans }}
                        </div>
                    </x-table.cell>
                    <x-table.cell>
                        <div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">
                                <span style="{{ $cable->purpose_color }}"
                                      class="text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">
                                    {{ $cable->cable_purpose->name }}
                                </span>
                            </div>
                        </div>
                    </x-table.cell>
                    <x-table.cell class="text-right text-sm font-medium">
                        <button type="button" wire:click="toggleCommentModal({{ $cable->id }})" class="px-3 py-3 {{ $cable->comment ? 'bg-red-500 hover:bg-red-600' : 'bg-gray-500 hover:bg-gray-600' }} text-white text-xs rounded-md">
                            <i class="fas fa-comments fa-sm" aria-hidden="true" title="{{ __('Comment') }}"></i>
                        </button>
                        <button type="button" wire:click="toggleUpdateModal({{ $cable->id }})" class="px-3 py-3 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-md">
                            <i class="fas fa-edit fa-sm" aria-hidden="true" title="{{ __('Edit') }}"></i>
                        </button>
                        <button type="button" wire:click="confirmDelete({{ $cable->id }})" class="px-3 py-3 bg-red-500 hover:bg-red-600 text-white text-xs rounded-md">
                            <i class="fas fa-trash fa-sm" aria-hidden="true" title="{{ __('Delete') }}"></i>
                        </button>
                    </x-table.cell>
                </x-table.row>
            @empty
                <x-table.row wire:key="row-empty">
                    <x-table.cell colspan="8">
                        <div class="flex justify-center items-center">
                            <span class="py-8 text-base font-medium text-gray-400 uppercase">{{ __('There is no such cable registered in the system') }} ...</span>
                        </div>
                    </x-table.cell>
                </x-table.row>
            @endforelse
        </x-slot>
    </x-table>

    <div class="mt-5">
        {{ $cables->links() }}
    </div>

    <x-flash/>

    <form method="POST" wire:submit.prevent="save">

        @csrf

        <x-modals.comment wire:model.defer="showCommentModal">
            <x-slot name="title">{{ __('Megjegyz??s szerkeszt??se - :NAME', ['name' => $currentCable?->full_name]) }}</x-slot>
            <x-slot name="body">
                {{  __('A rendszerben t??rolt k??belekhez megjegyz??s f??zhet??. A megjegyz??sek a k??s??bbiekben az adott k??bel adatlapj??n
                m??dos??that??k vagy t??r??lhet??k. Amennyiben egy k??belhez megjegyz??s kapcsol??dik, akkor a lista n??zetben a megjegyz??sek
                ikon piros sz??nre v??lt.') }}
                <label for="comment" class="block mt-2 mb-2 text-sm text-gray-900">
                    {{ __('Comment') }}:
                </label>
                <textarea id="comment"
                          rows="4"
                          class="text-left p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                          wire:model.defer="currentCable.comment"
                ></textarea>
            </x-slot>
            <x-slot name="footer">
                <x-forms.button
                    type="submit"
                    class="border-transparent bg-red-600 text-white hover:bg-red-700 focus:ring-red-500"
                >
                    {{ __('Save') }}
                </x-forms.button>
                <x-forms.button
                    class="mt-3 border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:ring-indigo-500 sm:mt-0"
                    wire:click="$set('showCommentModal', false)"
                >
                    {{ __('Cancel') }}
                </x-forms.button>
            </x-slot>
        </x-modals.comment>

    </form>

    <form method="POST" wire:submit.prevent="delete">

        @csrf
        @method('DELETE')

        <x-modals.delete wire:model.defer="showDeleteModal">
            @if ($selectedItems)
                <x-slot name="title">{{ __('Delete cables') }}</x-slot>
            @else
                <x-slot name="title">{{ __('K??bel t??rl??se - :NAME', ['name' => $currentCable->full_name]) }}</x-slot>
            @endif
            <x-slot name="body">
                @if ($showDeleteModal && $selectedItems )
                    {{ __('Biztos benne, hogy t??r??lni k??v??nja a kiv??lasztott k??beleket? A m??velet nem visszavonhat??
                    v??ltoz??st eredm??nyez a rendszer adatb??zis??ban.') }}
                    <label for="message" class="block mt-2 mb-2 text-sm text-gray-900">
                        {{ __('A t??r??lni k??v??nt k??belek list??ja (els?? 10 db.)') }}:
                    </label>
                    <textarea id="message"
                              rows="4"
                              class="text-left p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                    >@foreach (collect($selectedItems)->take(10) as $item){{ trim($cables->firstWhere('id', $item)->full_name).PHP_EOL }}@endforeach</textarea>
                @else
                    {{  __('Biztos benne, hogy t??r??lni k??v??nja a kiv??lasztott k??belt? A m??velet nem visszavonhat??
                    v??ltoz??st eredm??nyez a rendszer adatb??zis??ban.') }}
                @endif
            </x-slot>
            <x-slot name="footer">
                <x-forms.button
                    type="submit"
                    class="border-transparent bg-red-600 text-white hover:bg-red-700 focus:ring-red-500"
                >
                    {{ __('Delete') }}
                </x-forms.button>
                <x-forms.button
                    class="mt-3 border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:ring-indigo-500 sm:mt-0"
                    wire:click="$set('showDeleteModal', false)"
                >
                    {{ __('Cancel') }}
                </x-forms.button>
            </x-slot>
        </x-modals.delete>

    </form>

    <form method="POST" wire:submit.prevent="update">

        @csrf

        <x-modals.dialog wire:model.defer="showUpdateModal">

            <x-slot name="title">{{ __('Mass edit of cables') }}</x-slot>

            <x-slot name="content">

                <p class="col-span-6 sm:col-span-3 lg:col-span-6 text-gray-700 dark:text-gray-200 mb-4">
                    {{ __('In case of mass edit you can only change the cable pair status and the cable purpose for the selected cables.') }}
                </p>

                <div class="col-span-6 sm:col-span-3 lg:col-span-6" wire:key="cablePairStatus">
                    @php
                        $cablePairStatuses = \App\Models\CablePairStatus::all();
                    @endphp
                    <label for="cablePairStatus" class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('K??belp??r st??tusza') }}*</label>
                    <select id="cablePairStatus"
                            name="cablePairStatus"
                            wire:model.defer="cablePairStatusId"
                            required
                            class="mt-1 block w-full rounded-md
                                   border border-gray-300 bg-white
                                   py-2 px-3 shadow-sm
                                   focus:border-indigo-500 focus:outline-none focus:ring-indigo-500
                                   sm:text-sm">
                        <option value="0" disabled>{{ __('Please select') }}!</option>
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

                <div class="col-span-6 sm:col-span-3 col-span-6 mt-4 mb-4" wire:key="cablePurpose">
                    @php
                        $cablePurposes = \App\Models\CablePurpose::all();
                    @endphp
                    <label for="cablePurpose" class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('K??bel felhaszn??l??si m??dja') }}*</label>
                    <select id="cablePurpose"
                            name="cablePurpose"
                            wire:model.defer="cablePurposeId"
                            required
                            class="mt-1 block w-full rounded-md
                                   border border-gray-300 bg-white
                                   py-2 px-3 shadow-sm
                                   focus:border-indigo-500 focus:outline-none focus:ring-indigo-500
                                   sm:text-sm">
                        <option value="0" disabled>{{ __('Please select') }}!</option>
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

            </x-slot>

            <x-slot name="footer">
                <x-primary-button>{{ __('Modify') }}</x-primary-button>
                <x-secondary-button wire:click="toggleUpdateModal" class="ml-2">{{ __('Cancel') }}</x-secondary-button>
            </x-slot>

        </x-modals.dialog>

    </form>

    <div wire:loading.delay wire:target="exportSelected">
        <x-modals.export>
            {{ __('Exporting cables in progress') }} ...
        </x-modals.export>
    </div>

</div>
