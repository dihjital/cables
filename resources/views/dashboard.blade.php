<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Műszerfal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-4 gap-4">
                        <x-cards.cables>
                            Lehetőség van a rendszerben a kábelek listázására, tömeges illetve egyedi felvitelére, szerkesztésére és exportálására illetve importálására is .csv formátumból.
                        </x-cards.cables>
                        <x-cards.connectivity_devices>
                            Lehetőség van a rendszerben a kapcsolati eszközök listázására, tömeges illetve egyedi felvitelére, szerkesztésére és exportálására illetve importálására is .csv formátumból.
                        </x-cards.connectivity_devices>
                        <x-cards.locations>
                            Lehetőség van a rendszerben a lokációk és zónák listázására, felvitelére és szerkesztésére. A zónákat lokációkhoz lehet rendelni és ugyanez megtehető a lokációk és zónák összerendelése kapcsán is.
                        </x-cards.locations>
                        <x-cards.users>
                            Lehetőség van a rendszerben a felhasználók felvitelére és szerkesztésére. Beállítható, hogy mely felhasználók aktívak vagy inaktívak, illet, hogy melyek rendelkeznek adminisztrátori jogokkal.
                        </x-cards.users>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
