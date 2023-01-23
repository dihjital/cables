@props(['action'])

<form method="POST" action="{{ $action }}" class="w-full border-gray-200 p-6 rounded-xl bg-white mb-6 ">
    @csrf
    <header class="flex mb-5">
        <h2 class="font-semibold">Zóna és lokáció összekapcsolása</h2>
    </header>
    <div class="mb-1 inline-flex items-center">
        <label class="block uppercase font-bold text-xs text-gray-700 mr-2" for="zone_name">
            Zóna neve
        </label>
        <input class="border border-gray-400 p-2 h-6 text-xs"
               type="text"
               name="zone_name"
               id="zone_name"
               maxlength="5"
               value="{{ old('zone_name') }}"
               required>
        <label class="block uppercase font-bold text-xs text-gray-700 ml-5 mr-2" for="location_name">
            Lokáció neve
        </label>
        <input class="border border-gray-400 p-2 h-6 text-xs"
               type="text"
               name="location_name"
               id="location_name"
               maxlength="5"
               value="{{ old('location_name') }}"
               required>
    </div>
    <div class="flex justify-end mt-5 border-t border-gray-200 pt-6">
        <button type="submit" class="bg-blue-500 text-white uppercase font-semibold text-xs py-2 px-10 rounded-2xl hover:bg-blue-600">
            Új zóna
        </button>
    </div>
    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li class="text-red-500 text-xs">{{ $error }}</li>
            @endforeach
        </ul>
    @endif
</form>
