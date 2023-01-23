<div>
<x-forms.dropdown
    wire:model.debounce.300ms="zoneDropDown"
    label="Zónák"
    padding="px-0 lg:ml-0"
    required
    maxlength="2"
>
    @if(strlen($zoneDropDown) >= 1 and $showZoneDropDown)
        <x-forms.dropdown.list>
            @forelse ($zones as $zone)
                <x-forms.dropdown.item>
                    <a wire:click="selectZone({{ $zone->id }})" href="#"
                       class="flex items-center px-4 py-4 hover:bg-gray-200 transition ease-in-out duration-150">
                        <img src="" alt="" class="w-10">
                        <div class="ml-4 leading-tight">
                            <div class="font-semibold">{{ $zone->name }}</div>
                            <div class="text-gray-600"></div>
                        </div>
                    </a>
                </x-forms.dropdown.item>
            @empty
                <x-forms.dropdown.item>
                    <div class="px-4 py-4">Nincsen ilyen elem "{{ $zoneDropDown }}" a listában</div>
                </x-forms.dropdown.item>
            @endforelse
        </x-forms.dropdown.list>
    @endif
</x-forms.dropdown>
</div>
