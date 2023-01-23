@props(['name', 'label'])

<div class="md:w-1/3">
    <label  x-data="{ error: false }"
            x-init="error = {!! $errors->has($name) ? 'true' : 'false' !!}"
            class="block text-gray-500 font-light capitalize whitespace-nowrap md:text-right mb-1 md:mb-0 pr-4"
            :class="{ 'text-red-500': error }"
            for="{{ $name }}"
    >
        {{ $label }}
    </label>
</div>
