<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Rapport mensuel des absents — {{ $moisLabel }}
            </h2>
            @if($totalJours > 0 && $membresAbsents->count() > 0)
            <a href="{{ route('dashboard.absents-mensuels.pdf', ['mois' => $mois, 'annee' => $annee]) }}">
                <x-primary-button type="button" style="background-color: rgb(34 197 94) !important; outline-color: rgb(34 197 94) !important;">
                    Exporter en PDF
                </x-primary-button>
            </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Filtre mois / année --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="GET" action="{{ route('dashboard.absents-mensuels') }}" class="flex flex-wrap gap-4 items-end">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Mois</label>
                            <select name="mois" class="border-gray-300 rounded-md shadow-sm text-sm">
                                @foreach(['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'] as $i => $nom)
                                    <option value="{{ $i + 1 }}" {{ $mois == $i + 1 ? 'selected' : '' }}>{{ $nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Année</label>
                            <select name="annee" class="border-gray-300 rounded-md shadow-sm text-sm">
                                @for($y = now()->year; $y >= now()->year - 3; $y--)
                                    <option value="{{ $y }}" {{ $annee == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <x-primary-button type="submit">Afficher</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            @if($cultes->count() === 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-500">
                    Aucun culte passé trouvé pour {{ $moisLabel }}.
                </div>
            @else

                {{-- Statistiques --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $totalJours }}</div>
                        <div class="text-sm text-gray-500">Jours de culte</div>
                        <div class="text-xs text-gray-400 mt-1">{{ $cultes->count() }} culte(s) au total</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                        <div class="text-2xl font-bold text-gray-700">{{ $totalPermanents }}</div>
                        <div class="text-sm text-gray-500">Membres permanents</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                        <div class="text-2xl font-bold text-red-600">{{ $membresAbsents->count() }}</div>
                        <div class="text-sm text-gray-500">Membres avec absences</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $totalPermanents - $membresAbsents->count() }}</div>
                        <div class="text-sm text-gray-500">Présents à tous les jours</div>
                    </div>
                </div>

                @if($membresAbsents->count() === 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-green-700 font-medium">
                        Tous les membres permanents étaient présents à chaque jour de culte de {{ $moisLabel }}.
                    </div>
                @else

                {{-- Liste des jours de culte du mois --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-base font-semibold text-gray-700 mb-3">Jours de culte du mois</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($cultesByDay as $dateKey => $cultesOfDay)
                                <span class="inline-block bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded-full">
                                    {{ \Carbon\Carbon::parse($dateKey)->format('d/m') }}
                                    @if($cultesOfDay->count() > 1)
                                        <span class="text-gray-400">({{ $cultesOfDay->count() }} cultes)</span>
                                    @else
                                        — {{ $cultesOfDay->first()->name }}
                                    @endif
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Tableau des absents --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4 text-red-600">
                            Membres absents ({{ $membresAbsents->count() }})
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="text-left border-b bg-gray-50">
                                        <th class="py-2 px-3">N°</th>
                                        <th class="py-2 px-3">Nom</th>
                                        <th class="py-2 px-3">Prénom</th>
                                        <th class="py-2 px-3">Catégorie</th>
                                        <th class="py-2 px-3">Contact</th>
                                        <th class="py-2 px-3 text-center">Jours absents / Total</th>
                                        <th class="py-2 px-3 text-center">Taux présence</th>
                                        <th class="py-2 px-3">Dates d'absence</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($membresAbsents as $i => $data)
                                        @php
                                            $taux = $data['taux_presence'];
                                            $tauxColor = $taux >= 75 ? 'text-green-600' : ($taux >= 50 ? 'text-orange-500' : 'text-red-600');
                                        @endphp
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="py-2 px-3 text-gray-400">{{ $i + 1 }}</td>
                                            <td class="py-2 px-3 font-medium">{{ $data['member']->last_name }}</td>
                                            <td class="py-2 px-3">{{ $data['member']->first_name }}</td>
                                            <td class="py-2 px-3 text-gray-500">{{ $data['member']->category->name ?? '—' }}</td>
                                            <td class="py-2 px-3 text-gray-500">{{ $data['member']->phone ?? '—' }}</td>
                                            <td class="py-2 px-3 text-center">
                                                <span class="font-semibold text-red-600">{{ $data['nb_absences'] }}</span>
                                                <span class="text-gray-400">/ {{ $totalJours }}</span>
                                            </td>
                                            <td class="py-2 px-3 text-center font-semibold {{ $tauxColor }}">
                                                {{ $taux }}%
                                            </td>
                                            <td class="py-2 px-3">
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($data['dates_absences'] as $absence)
                                                        <span class="inline-block bg-red-50 text-red-700 text-xs px-2 py-0.5 rounded">
                                                            {{ $absence['date'] }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @endif
            @endif

            <div>
                <a href="{{ route('dashboard') }}">
                    <x-secondary-button type="button">Retour au tableau de bord</x-secondary-button>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
