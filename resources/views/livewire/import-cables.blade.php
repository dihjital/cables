<div class="float-right ml-2">

    <x-buttons.import wire:click="toggleModal()">
        Import
    </x-buttons.import>

    <form wire:submit.prevent="import" method="POST" enctype="multipart/form-data">

        @csrf

        <x-modals.import model="showImportModal">
            <x-slot name="title">Kábelek betöltése</x-slot>
            <x-slot name="body">
                @unless($upload)
                <p class="text-sm text-gray-500 break-normal">Válassza ki a betölteni kívánt állományt a gépéről. Az állománynak az
                előre megadott formátummal kell rendelkeznie a sikeres adatbetöltéshez.</p>
                <div class="mt-2 flex items-center justify-center w-full">
                    <label for="upload" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                         <div class="flex flex-col items-center justify-center pt-5 pb-6">
                             <svg aria-hidden="true" class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                             <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                             <p class="text-xs text-gray-500">.csv, .txt (max: 10k)</p>
                         </div>
                         <input id="upload" wire:model="upload" accept=".csv,.txt" type="file" class="hidden" />
                     </label>
                </div>
                @error('upload') <div class="mt-3 text-red-500 text-sm">{{ $message }}</div>@enderror
                @else
                    @unless(count($importFailures))
                        <x-forms.import-select>
                            <x-slot name="label">
                                <x-forms.label name="fieldColumnMap.full_name" :label="__('Teljes név')"></x-forms.label>
                            </x-slot>
                            <x-slot name="select">
                                <x-forms.select wire:model="fieldColumnMap.full_name" name="fieldColumnMap.full_name">
                                    <option wire:key="key-full_name" value="" disabled selected>Kérem válasszon!</option>
                                    @foreach($columns[0][0] as $column)
                                        <option wire:key="key-full_name-{{ $loop->index }}">{{ $column }}</option>
                                    @endforeach
                                </x-forms.select>
                            </x-slot>
                        </x-forms.import-select>
                        <x-forms.import-select>
                            <x-slot name="label">
                                <x-forms.label name="fieldColumnMap.startCD" :label="__('Kezdőeszköz')"></x-forms.label>
                            </x-slot>
                            <x-slot name="select">
                                <x-forms.select wire:model="fieldColumnMap.startCD" name="fieldColumnMap.startCD">
                                    <option wire:key="key-startCD" value="" disabled selected>Kérem válasszon!</option>
                                    @foreach($columns[0][0] as $column)
                                        <option wire:key="key-startCD-{{ $loop->index }}">{{ $column }}</option>
                                    @endforeach
                                </x-forms.select>
                            </x-slot>
                        </x-forms.import-select>
                        <x-forms.import-select>
                            <x-slot name="label">
                                <x-forms.label name="fieldColumnMap.start" :label="__('Kezdőpont')"></x-forms.label>
                            </x-slot>
                            <x-slot name="select">
                                <x-forms.select wire:model="fieldColumnMap.start" name="fieldColumnMap.start">
                                    <option wire:key="key-start" value="" disabled selected>Kérem válasszon!</option>
                                    @foreach($columns[0][0] as $column)
                                        <option wire:key="key-start-{{ $loop->index }}">{{ $column }}</option>
                                    @endforeach
                                </x-forms.select>
                            </x-slot>
                        </x-forms.import-select>
                        <x-forms.import-select>
                            <x-slot name="label">
                                <x-forms.label name="fieldColumnMap.endCD" :label="__('Végeszköz')"></x-forms.label>
                            </x-slot>
                            <x-slot name="select">
                                <x-forms.select wire:model="fieldColumnMap.endCD" name="fieldColumnMap.endCD">
                                    <option wire:key="key-endCD" value="" disabled selected>Kérem válasszon!</option>
                                    @foreach($columns[0][0] as $column)
                                        <option wire:key="key-endCD-{{ $loop->index }}">{{ $column }}</option>
                                    @endforeach
                                </x-forms.select>
                            </x-slot>
                        </x-forms.import-select>
                        <x-forms.import-select>
                            <x-slot name="label">
                                <x-forms.label name="fieldColumnMap.end" :label="__('Végpont')"></x-forms.label>
                            </x-slot>
                            <x-slot name="select">
                                <x-forms.select wire:model="fieldColumnMap.end" name="fieldColumnMap.end">
                                    <option wire:key="key-end" value="" disabled selected>Kérem válasszon!</option>
                                    @foreach($columns[0][0] as $column)
                                        <option wire:key="key-end-{{ $loop->index }}">{{ $column }}</option>
                                    @endforeach
                                </x-forms.select>
                            </x-slot>
                        </x-forms.import-select>
                        <x-forms.import-select>
                            <x-slot name="label">
                                <x-forms.label name="fieldColumnMap.i_time" :label="__('Telepítés dátuma')"></x-forms.label>
                            </x-slot>
                            <x-slot name="select">
                                <x-forms.select wire:model="fieldColumnMap.i_time" name="fieldColumnMap.i_time">
                                    <option wire:key="key-i_time" value="" disabled selected>Kérem válasszon!</option>
                                    @foreach($columns[0][0] as $column)
                                        <option wire:key="key-i_time-{{ $loop->index }}">{{ $column }}</option>
                                    @endforeach
                                </x-forms.select>
                            </x-slot>
                        </x-forms.import-select>
                        <x-forms.import-select>
                            <x-slot name="label">
                                <x-forms.label name="fieldColumnMap.status" :label="__('Állapot')"></x-forms.label>
                            </x-slot>
                            <x-slot name="select">
                                <x-forms.select wire:model="fieldColumnMap.status" name="fieldColumnMap.status">
                                    <option wire:key="key-status" value="" disabled selected>Kérem válasszon!</option>
                                    @foreach($columns[0][0] as $column)
                                        <option wire:key="key-status-{{ $loop->index }}">{{ $column }}</option>
                                    @endforeach
                                </x-forms.select>
                            </x-slot>
                        </x-forms.import-select>
                        <x-forms.import-select>
                            <x-slot name="label">
                                <x-forms.label name="fieldColumnMap.purpose" :label="__('Felhasználás módja')"></x-forms.label>
                            </x-slot>
                            <x-slot name="select">
                                <x-forms.select wire:model="fieldColumnMap.purpose" name="fieldColumnMap.purpose">
                                    <option wire:key="key-purpose" value="" disabled selected>Kérem válasszon!</option>
                                    @foreach($columns[0][0] as $column)
                                        <option wire:key="key-purpose-{{ $loop->index }}">{{ $column }}</option>
                                    @endforeach
                                </x-forms.select>
                            </x-slot>
                        </x-forms.import-select>
                    @else
                        <div class="flex p-4 mb-1 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                            <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Danger</span>
                            <div>
                                <span><h3>Tömeges betöltés sikertelen!</h3></span>
                                <ul class="mt-1.5 ml-4 list-disc list-inside">
                                    @foreach($importFailures as $failure)
                                    <li>Row: {{ $failure->row() }}</li>
                                    <li>Attribute: {{ $failure->attribute() }}</li>
                                </ul>
                                    <details>
                                        <summary><strong>Errors:</strong></summary>
                                        <ul class="mb-1.5 ml-4 list-disc list-inside">
                                        @foreach($failure->errors() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                        </ul>
                                    </details>
                                    <details>
                                        <summary><strong>Values:</strong></summary>
                                        <ul class="ml-4 list-disc list-inside">
                                        @foreach($failure->values() as $key => $value)
                                            <li>{{ $key }}: {{ $value }}</li>
                                       @endforeach
                                        </ul>
                                    </details>
                                    @endforeach
                            </div>
                        </div>
                    @endunless
                @endunless
            </x-slot>
            <x-slot name="footer">
                <x-forms.button
                    type="submit"
                    class="border-transparent bg-red-600 text-white hover:bg-red-700 focus:ring-red-500"
                >
                    Betöltés
                </x-forms.button>
                <x-forms.button
                    class="mt-3 border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:ring-indigo-500 sm:mt-0"
                    wire:click="toggleModal()"
                >
                    Mégsem
                </x-forms.button>
            </x-slot>
        </x-modals.import>

    </form>

</div>
