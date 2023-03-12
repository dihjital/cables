<label for="pageSize" class="w-1/12 block text-sm font-medium text-gray-700">{{ __('Page size') }}:</label>
<select id="pageSize"
        wire:model="pageSize"
        class="w-1/12 mr-4 block rounded-md
               border border-gray-300 bg-white
               py-2 px-3 shadow-sm
               focus:border-indigo-500 focus:outline-none focus:ring-indigo-500
               sm:text-sm">
    <option value="10">10</option>
    <option value="25">25</option>
    <option value="50">50</option>
</select>
