@extends('layouts.app')

@section('content')

    <h3 class="text-3xl font-semibold capitalize mb-8 pt-5">Kapcsolati eszközök listája</h3>

    <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full rounded-b">
                        <thead class="border-b bg-gray-200">
                        <tr>
                            <th scope="col" class="text-sm font-semibold font-medium text-gray-900 px-6 py-4 text-left">ID</th>
                            <th scope="col" class="text-sm font-semibold font-medium text-gray-900 px-6 py-4 text-left">Név</th>
                            <th scope="col" class="text-sm font-semibold font-medium text-gray-900 px-6 py-4 text-left">Zóna</th>
                            <th scope="col" class="text-sm font-semibold font-medium text-gray-900 px-6 py-4 text-left">Típus</th>
                            <th scope="col" class="text-sm font-semibold font-medium text-gray-900 px-6 py-4 text-left">Lokáció</th>
                            <th scope="col" class="text-sm font-semibold font-medium text-gray-900 px-6 py-4 text-left">Kezdőpont</th>
                            <th scope="col" class="text-sm font-semibold font-medium text-gray-900 px-6 py-4 text-left">Végpont</th>
                            <th scope="col" class="text-sm font-semibold font-medium text-gray-900 px-6 py-4 text-left">Tulajdonos</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($connectivity_devices as $cd)
                                <tr class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-light text-gray-900">{{ $cd->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-light text-gray-900">
                                        <a href="/zones?zone={{ $cd->zone->name }}">{{ $cd->zone->name }}</a>
                                        /
                                        <a href="/locations?location={{ $cd->location->name }}">{{ $cd->location->name }}</a>
                                        -
                                        <a href="#">{{ $cd->name }}</a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-light text-gray-900">{{ $cd->zone->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-light text-gray-900">{{ $cd->connectivity_device_type->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-light text-gray-900">{{ $cd->location->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-light text-gray-900">{{ $cd->start }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-light text-gray-900">{{ $cd->end }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-light text-gray-900">{{ $cd->owner->name }}</td>
                                    <td class="whitespace-nowrap">
                                        <button class="bg-red-500 uppercase font-semibold text-xs hover:bg-red-600 text-white font-light py-2 px-4 rounded-xl">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg>
                                        </button>
                                        <x-buttons.delete :action="route('connectivity_device.delete', $cd->id)" />
                                    </td>
                                </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-forms.new-connectivity_device :action="route('connectivity_device.new')" />

    {{ $connectivity_devices->links() }}

    <x-flash />

@endsection
