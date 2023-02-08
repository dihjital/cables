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

            @php
                $actionTypes = \App\Enums\Action::cases();
            @endphp

            <div class="md:w-10/12 inline-flex rounded-md justify-end float-right" role="group">
                @foreach (array_column($actionTypes, 'name') as $name)
                    <button type="button"
                            wire:click="$set('search.action', '{{ $name }}')"
                            class="px-4 py-2 text-sm font-medium text-gray-900 bg-white
                                             {{ $loop->iteration % 2 ? 'border' : 'border-t border-b' }}  border-gray-200
                                             {{ $loop->first ? 'rounded-l-lg' : ($loop->last ? 'rounded-r-md' : '') }}
                                             hover:bg-gray-100 hover:text-blue-700
                                             focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700
                                             dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600
                                             dark:focus:ring-blue-500 dark:focus:text-white">
                        {{ $name }}
                    </button>
                @endforeach
            </div>

        </div>
    </div>

    <x-table>
        <x-slot name="head">
            <x-table.heading class="w-1/12"></x-table.heading>
            <x-table.heading class="w-3/12">
                <div class="flex items-center">
                    <button wire:click="sortBy('user.name')"
                            class="leading-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Név
                    </button>
                    <x-table.sort-icon field="user.name"
                                       :sortField="$sortField" :sortDirection="$sortDirection"></x-table.sort-icon>
                </div>
            </x-table.heading>
            <x-table.heading class="w-2/12">
                <div class="flex items-center">
                    <button wire:click="sortBy('history.action')"
                            class="leading-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Művelet
                    </button>
                    <x-table.sort-icon field="history.action" :sortField="$sortField"
                                       :sortDirection="$sortDirection"></x-table.sort-icon>
                </div>
            </x-table.heading>
            <x-table.heading class="w-3/12">Objektum</x-table.heading>
            <x-table.heading class="w-3/12">
                <div class="flex items-center">
                    <button wire:click="sortBy('history.updated_at')"
                            class="leading-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Módosítás dátuma
                    </button>
                    <x-table.sort-icon field="history.updated_at" :sortField="$sortField"
                                       :sortDirection="$sortDirection"></x-table.sort-icon>
                </div>
            </x-table.heading>
        </x-slot>
        <x-slot name="body">
            @foreach($historyItems as $item)
                <x-table.row wire:loading.class.delay="opacity-75" wire:key="row-{{ $item->id }}">
                    <x-table.cell>
                        <button type="button" wire:click="selectItem({{ $item }})">
                            @if($selectedItem == $item->id)
                            <span><i class="fa-solid fa-chevron-up"></i></span>
                            @else
                            <span><i class="fa-solid fa-chevron-down"></i></span>
                            @endif
                        </button>
                    </x-table.cell>
                    <x-table.cell>
                        @php
                            $user = \App\Models\User::find($item->user_id)
                        @endphp
                        <div class="text-sm font-medium text-gray-900">
                            {{ $user->name }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $user->email }}
                        </div>
                    </x-table.cell>
                    <x-table.cell>
                        <div class="text-sm font-medium text-gray-900">
                            {{ $item->action }}
                        </div>
                        <div class="text-sm text-gray-500">
                        </div>
                    </x-table.cell>
                    <x-table.cell>
                        @php
                            $model = $item->model_type::find($item->model_id)
                        @endphp
                        <div class="text-sm font-medium text-gray-900">
                            {{ $model->full_name ? $model->full_name : $model->name }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $item->model_type }}
                        </div>
                    </x-table.cell>
                    <x-table.cell>
                        <div class="text-sm font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($item->updated_at)->diffForhumans() }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $item->date_for_humans }}
                        </div>
                    </x-table.cell>
                </x-table.row>
                @if($selectedItem == $item->id)
                    <x-table.row id="detail-row-{{ $item->id }}"
                                 wire:key="detail-row-{{ $item->id }}">
                        <x-table.cell colspan="3">
                            <div class="text-sm font-medium text-gray-900">Eredeti értékek:</div>
                            @php
                                $before = (array) json_decode($item->before);
                            @endphp
                            @forelse($before as $attribute => $value)
                                <div class="text-sm text-gray-500">
                                    {{ __($model->{$attribute.'_description'}.": $value") }}
                                </div>
                            @empty
                            @endforelse
                        </x-table.cell>
                        <x-table.cell colspan="2">
                            <div class="text-sm font-medium text-gray-900">Módosított értékek:</div>
                            @php
                                $after = (array) json_decode($item->after);
                            @endphp
                            @forelse($after as $attribute => $value)
                                @if($value != $before[$attribute])
                                <div class="text-sm text-gray-500">
                                    {{ __($model->{$attribute.'_description'}.": $value") }}
                                </div>
                                @endif
                            @empty
                            @endforelse
                        </x-table.cell>
                    </x-table.row>
                @endif
            @endforeach
        </x-slot>
    </x-table>

    <div class="mt-4">
        {{ $historyItems->links() }}
    </div>

    <script>

        Livewire.hook('message.processed', (message, component) => {
            let row = document.querySelector('#detail-row-4');
            row.animate([
                { transform: `translateX(100px)` },
                { transform: `translateX(0px)` }
            ], { duration: 1500, easing: 'ease' });
        })

    </script>

</div>
