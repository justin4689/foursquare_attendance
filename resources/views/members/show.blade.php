<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails du Membre') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <div>
                        <div class="text-sm text-gray-500">{{ __('Nom') }}</div>
                        <div class="font-semibold">{{ $member->last_name }}</div>
                    </div>

                    <div>
                        <div class="text-sm text-gray-500">{{ __('Prénom') }}</div>
                        <div class="font-semibold">{{ $member->first_name }}</div>
                    </div>

                    <div>
                        <div class="text-sm text-gray-500">{{ __('Téléphone') }}</div>
                        <div class="font-semibold">{{ $member->phone ?? '—' }}</div>
                    </div>

                    <div>
                        <div class="text-sm text-gray-500">{{ __('Catégorie') }}</div>
                        <div class="font-semibold">{{ $member->category->name ?? '—' }}</div>
                    </div>

                    <div class="flex gap-2 pt-4">
                        <a href="{{ route('members.edit', $member) }}">
                            <x-primary-button type="button">
                                {{ __('Modifier') }}
                            </x-primary-button>
                        </a>

                        <a href="{{ route('members.index') }}">
                            <x-secondary-button type="button">
                                {{ __('Retour à la liste') }}
                            </x-secondary-button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
