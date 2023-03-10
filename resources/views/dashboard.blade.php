<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                        <div class="p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="stats" role="tabpanel" aria-labelledby="stats-tab">
                            <dl class="grid max-w-screen-xl grid-cols-2 gap-8 p-4 mx-auto text-gray-900 sm:grid-cols-3 xl:grid-cols-3 dark:text-white sm:p-8">
                                <div class="flex flex-col items-center justify-center">
                                    <dt class="mb-2 text-3xl font-extrabold">
                                        {{ number_format(\App\Models\Cable::count(), 0, '', ',') }}
                                    </dt>
                                    <dd class="font-light text-gray-500 dark:text-gray-400">{{ __('Number of cables') }}</dd>
                                </div>
                                <div class="flex flex-col items-center justify-center">
                                    <dt class="mb-2 text-3xl font-extrabold">
                                        {{ number_format(\App\Models\CablePair::distinct('cable_id')
                                            ->where('cable_pair_status_id', 2)
                                            ->count(), 0, '', ',') }}
                                    </dt>
                                    <dd class="font-light text-gray-500 dark:text-gray-400">{{ __('Number of active cables') }}</dd>
                                </div>
                                <div class="flex flex-col items-center justify-center">
                                    <dt class="mb-2 text-3xl font-extrabold">
                                        {{ number_format(\DB::table('cables')
                                            ->where('cable_type_id', 3)
                                            ->count(), 0, '', ',') }}
                                    </dt>
                                    <dd class="font-light text-gray-500 dark:text-gray-400">{{ __('Number of fiber cables') }}</dd>
                                </div>
                                <div class="flex flex-col items-center justify-center">
                                    <dt class="mb-2 text-3xl font-extrabold">
                                        {{ \App\Models\ConnectivityDevice::count() }}
                                    </dt>
                                    <dd class="font-light text-gray-500 dark:text-gray-400">{{ __('Number of connectivity devices') }}</dd>
                                </div>
                                <div class="flex flex-col items-center justify-center">
                                    <dt class="mb-2 text-3xl font-extrabold">
                                        {{ number_format(\App\Models\CablePair::distinct('conn_dev_id', 'conn_point')
                                            ->count(), 0, '', ',') }}
                                    </dt>
                                    <dd class="font-light text-gray-500 dark:text-gray-400">{{ __('Number of connection points') }}</dd>
                                </div>
                                <div class="flex flex-col items-center justify-center">
                                    <dt class="mb-2 text-3xl font-extrabold">
                                        {{ \App\Models\Owner::count() }}
                                    </dt>
                                    <dd class="font-light text-gray-500 dark:text-gray-400">{{ __('Number of owners') }}</dd>
                                </div>
                            </dl>
                        </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
