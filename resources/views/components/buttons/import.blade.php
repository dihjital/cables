<button
    {{ $attributes(['class' => 'px-5 py-2 bg-gray-600 hover:bg-gray-700 text-white text-xs rounded-xl']) }}
>
    <i class="fas fa-file-import fa-xl mr-2" aria-hidden="true"></i>
    {{ $slot }}
</button>
