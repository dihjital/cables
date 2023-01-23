@props(['action', 'buttonText' => __('Delete')])

<div x-data="{ initial: true, deleting: false }" class="text-sm flex items-center">
    <button
        x-on:click.prevent="deleting = true; initial = false"
        x-show="initial"
        x-on:deleting.window="$el.disabled = true"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        class="text-white uppercase font-semibold text-xs py-2 px-4 rounded-2xl bg-red-500 hover:bg-red-600 disabled:opacity-50"
    >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
        <!-- {{ $buttonText }} //-->
    </button>

    <div
        x-show="deleting"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90"
        class="flex items-center space-x-3"
    >
        <span class="dark:text-black">@lang('Are you sure?')</span>

        <form x-on:submit="$dispatch('deleting')" method="post" action="{{ $action }}">
            @csrf
            @method('delete')

            <button
                x-on:click="$el.form.submit()"
                x-on:deleting.window="$el.disabled = true"
                type="submit"
                class="text-white uppercase text-xs font-semibold py-2 px-4 rounded-2xl bg-red-600 hover:bg-red-700 disabled:opacity-50"
            >
                @lang('Yes')
            </button>

            <button
                x-on:click.prevent="deleting = false; setTimeout(() => { initial = true }, 150)"
                x-on:deleting.window="$el.disabled = true"
                class="text-white uppercase text-xs font-semibold py-2 px-4 rounded-2xl bg-gray-600 hover:bg-gray-700 disabled:opacity-50"
            >
                @lang('No')
            </button>
        </form>
    </div>
</div>
