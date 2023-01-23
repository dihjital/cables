@props(['name', 'label'])

<div class="md:flex md:items-center mb-6">
    <x-forms.label name="{{ $name }}" label="{{ $label }}"></x-forms.label>
    <x-forms.field>
        <input
                x-data="{ error: false }"
                x-init="error = {!! $errors->has($name) ? 'true' : 'false' !!}"
                class="block w-full border-b focus:ring-white focus:border-b-2 text-gray-700 outline-0 rounded py-3 px-4 mb-3 leading-tight focus:bg-white"
                :class="{ 'border-red-500 text-red-700': error, 'border-blue-200': !error }"
                name="{{ $name }}"
                id="{{ $name }}"
                placeholder="{{ $attributes['placeholder'] }}"
                {{ $attributes(['value' => old($name)]) }}
        >
        @error($name)
        <span class="inline-flex text-sm text-red-700">{{ $message }}</span>
        @enderror
    </x-forms.field>
</div>
