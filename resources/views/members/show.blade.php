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

                    <div class="mb-4">
                        <x-input-label :value="__('Téléphone')" />
                        <p class="mt-1 text-gray-900">{{ $member->phone ?? '—' }}</p>
                    </div>

                    <div class="mb-4">
                        <x-input-label :value="__('Type de membre')" />
                        <div class="mt-1">
                            @if($member->type == 'permanent')
                                <span class="bg-green-100 text-green-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-green-200 dark:text-green-900">Permanent</span>
                            @elseif($member->type == 'invite')
                                <span class="bg-orange-100 text-orange-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-orange-200 dark:text-orange-900">Invité</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="text-sm text-gray-500">{{ __('Catégorie') }}</div>
                        <div class="font-semibold">{{ $member->category->name ?? '—' }}</div>
                    </div>

                    @if($member->type == 'permanent')
                        <div>
                            <div class="text-sm text-gray-500">{{ __('Lieu d\'habitation') }}</div>
                            <div class="font-semibold">{{ $member->lieu_habitation ?? '—' }}</div>
                        </div>

                        <div>
                            <div class="text-sm text-gray-500">{{ __('Anniversaire (jour/mois)') }}</div>
                            <div class="font-semibold">{{ $member->anniversaire_jour_mois ?? '—' }}</div>
                        </div>
                    @endif

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
