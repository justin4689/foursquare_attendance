<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Créer un culte') }}
        </h2>
    </x-slot>

    

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('cultes.store') }}" class="space-y-4">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Nom du culte')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="date" :value="__('Date')" />
                            <x-text-input id="date" name="date" type="date" class="mt-1 block w-full" :value="old('date')" required />
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="heure" :value="__('Heure de début')" />
                                <x-text-input id="heure" name="heure" type="time" class="mt-1 block w-full" :value="old('heure')" placeholder="08:00" />
                                <x-input-error :messages="$errors->get('heure')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="fin" :value="__('Heure de fin')" />
                                <x-text-input id="fin" name="fin" type="time" class="mt-1 block w-full" :value="old('fin')" placeholder="10:00" />
                                <x-input-error :messages="$errors->get('fin')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <x-primary-button>
                                {{ __('Enregistrer') }}
                            </x-primary-button>

                            <a href="{{ route('cultes.index') }}">
                                <x-secondary-button type="button">
                                    {{ __('Annuler') }}
                                </x-secondary-button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
