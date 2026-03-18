<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails de la Catégorie') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <div>
                        <div class="text-sm text-gray-500">{{ __('Nom') }}</div>
                        <div class="font-semibold">{{ $category->name }}</div>
                    </div>

                    <div>
                        <div class="text-sm text-gray-500">{{ __('Description') }}</div>
                        <div class="font-semibold">{{ $category->description ?? '—' }}</div>
                    </div>

                    <div>
                        <div class="text-sm text-gray-500">{{ __('Membres') }} ({{ $category->members->count() }})</div>
                        <div class="mt-2">
                            @if($category->members->isNotEmpty())
                                <div class="space-y-2">
                                    @foreach($category->members as $member)
                                        <div class="flex justify-between items-center border-b pb-1">
                                            <span>{{ $member->first_name }} {{ $member->last_name }}</span>
                                            <a class="underline text-sm" href="{{ route('members.show', $member) }}">{{ __('Voir') }}</a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-gray-400">{{ __('Aucun membre dans cette catégorie.') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="flex gap-2 pt-4">
                        <a href="{{ route('categories.edit', $category) }}">
                            <x-primary-button type="button">
                                {{ __('Modifier') }}
                            </x-primary-button>
                        </a>

                        <a href="{{ route('categories.index') }}">
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
