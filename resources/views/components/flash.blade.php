@if(session()->has('success'))
    <div x-data="{ show: true }"
         x-init="setTimeout(() => show = false, 5000)"
         x-show="show"
         x-transition:enter="transition ease-in-out duration-1000 transform"
         x-transition:enter-start="-translate-x-5 opacity-0"
         x-transition:enter-end="translate-x-0 opacity-100"
         x-transition:leave="transition ease-in-out duration-1000"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed bg-blue-500 border-l-4 rounded-xl w-1/3 border-blue-700 text-white py-2 px-4 bottom-3 right-3 text-sm"
         role="alert"
    >
        <p class="font-bold">Success</p>
        <p>{{ session('success') }}</p>
    </div>
@endif
