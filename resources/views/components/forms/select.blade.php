@props(['name'])

<select
        x-data="{ error: false }"
        x-init="error = {!! $errors->has($name) ? 'true' : 'false' !!}"
        class="bg-gray-50 border border-gray-50 border-r-8 text-gray-800 text-sm rounded-lg focus:ring-blue-500 focus:border-gray-50 block w-full p-2.5"
        :class="{ 'border-red-500 text-red-700': error, 'border-blue-200': !error }"
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $attributes }}
>
    {{ $slot }}
</select>
@error($name)
<span class="inline-flex text-sm text-red-700">{{ $message }}</span>
@enderror
