<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Zónák listája') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <x-table>
                        <x-slot name="head">
                            <x-table.heading>#</x-table.heading>
                            <x-table.heading>Zóna neve</x-table.heading>
                            <x-table.heading>Lokáció neve</x-table.heading>
                        </x-slot>
                        <x-slot name="body">
                            @foreach ($zones as $zone)
                                @foreach ($zone->locations as $location)
                                    <x-table.row>
                                        @if ($loop->first)
                                            <x-table.cell>
                                                {{ $zone->id }}
                                            </x-table.cell>
                                            <x-table.cell>
                                                <a href="/zones?zone={{ $zone->name }}">{{ $zone->name }}</a>
                                            </x-table.cell>
                                        @else
                                            <x-table.cell colspan="2"></x-table.cell>
                                        @endif
                                        <x-table.cell>
                                            <a href="/locations?location={{ $location->name }}">{{ $location->name }}</a>
                                        </x-table.cell>
                                    </x-table.row>
                                @endforeach
                            @endforeach
                        </x-slot>
                    </x-table>

                    <div class="mt-4">
                        {{ $zones->links() }}
                    </div>

                </div>

            </div>
        </div>
    </div>


</x-app-layout>>
