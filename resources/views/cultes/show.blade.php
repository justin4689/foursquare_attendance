<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $culte->name }}
            </h2>


             
            

            @if($culte->statut === 'passé')
            <div  class="flex gap-3">
                <a href="{{ route('cultes.presence.edit', $culte) }}">
                    <x-primary-button type="button">
                        {{ __('Faire le pointage') }}
                    </x-primary-button>
                </a>

                <a href="{{ route('cultes.pdf', $culte) }}">
                    <x-primary-button type="button" class="bg-green-600 hover:bg-green-700 focus:bg-green-700" style="background-color: rgb(34 197 94) !important; outline-color: rgb(34 197 94) !important;">
                        {{ __('Exporter en PDF') }}
                    </x-primary-button>
                </a>
                </div>
            @endif

        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Infos culte -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <div>
                        <div class="text-sm text-gray-500">{{ __('Nom') }}</div>
                        <div class="font-semibold">{{ $culte->name }}</div>
                    </div>

                    <div>
                        <div class="text-sm text-gray-500">{{ __('Date') }}</div>
                        <div class="font-semibold">{{ $culte->date->format('d/m/Y') }} {{ $culte->heure->format('H:i') }} à {{ $culte->fin->format('H:i') }}</div>

                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Statistiques') }}</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $present->count() }}</div>
                            <div class="text-sm text-gray-500">{{ __('Présents') }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-600">{{ $absent->count() }}</div>
                            <div class="text-sm text-gray-500">{{ __('Absents') }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-600">{{ $present->count() + $absent->count() }}</div>
                            <div class="text-sm text-gray-500">{{ __('Total pointés') }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $presentGuests->count() }}</div>
                            <div class="text-sm text-gray-500">{{ __('Invités présents') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Présents -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4 text-green-600">{{ __('Liste des présents') }} ({{ $present->count() }})</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left border-b">
                                    <th class="py-2 pr-4">{{ __('Nom') }}</th>
                                    <th class="py-2 pr-4">{{ __('Prénom') }}</th>
                                    <th class="py-2 pr-4">{{ __('Catégorie') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($present as $attendance)
                                    <tr class="border-b">
                                        <td class="py-2 pr-4">{{ $attendance->member->last_name }}</td>
                                        <td class="py-2 pr-4">{{ $attendance->member->first_name }}</td>
                                        <td class="py-2 pr-4">{{ $attendance->member->category->name ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="py-4" colspan="3">{{ __('Aucun présent.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Absents -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4 text-red-600">{{ __('Liste des absents') }} ({{ $absent->count() }})</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left border-b">
                                    <th class="py-2 pr-4">{{ __('Nom') }}</th>
                                    <th class="py-2 pr-4">{{ __('Prénom') }}</th>
                                    <th class="py-2 pr-4">{{ __('Catégorie') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($absent as $attendance)
                                    <tr class="border-b">
                                        <td class="py-2 pr-4">{{ $attendance->member->last_name }}</td>
                                        <td class="py-2 pr-4">{{ $attendance->member->first_name }}</td>
                                        <td class="py-2 pr-4">{{ $attendance->member->category->name ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="py-4" colspan="3">{{ __('Aucun absent.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('cultes.index') }}">
                    <x-secondary-button type="button">
                        {{ __('Retour à la liste') }}
                    </x-secondary-button>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
