<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Liste de présence mensuelle — {{ $moisLabel }}
            </h2>
            @if($totalJours > 0 && $presences->count() > 0)
            <a href="{{ route('dashboard.presences-mensuelles.pdf', ['mois' => $mois, 'annee' => $annee]) }}">
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
                    <form method="GET" action="{{ route('dashboard.presences-mensuelles') }}" class="flex flex-wrap gap-4 items-end">
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
                @php
                    $presentsParJour = $cultesByDay->keys()->mapWithKeys(
                        fn($dateKey) => [$dateKey => $presences->filter(fn($d) => $d['jours'][$dateKey])->count()]
                    );
                    $moyenne = $totalJours > 0 ? round($presentsParJour->sum() / $totalJours) : 0;
                @endphp

                {{-- Statistiques --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $totalJours }}</div>
                        <div class="text-sm text-gray-500">Jours de culte</div>
                        <div class="text-xs text-gray-400 mt-1">{{ $cultes->count() }} culte(s) au total</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                        <div class="text-2xl font-bold text-gray-700">{{ $presences->count() }}</div>
                        <div class="text-sm text-gray-500">Membres listés</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $presences->filter(fn($d) => $d['nb_presences'] === $totalJours)->count() }}</div>
                        <div class="text-sm text-gray-500">Présents à tous les jours</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $moyenne }}</div>
                        <div class="text-sm text-gray-500">Présents en moyenne / jour</div>
                    </div>
                </div>

                {{-- Grille de présence --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Présences par jour de culte</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="text-left border-b bg-gray-50">
                                        <th class="py-2 px-3">N°</th>
                                        <th class="py-2 px-3">Nom</th>
                                        <th class="py-2 px-3">Prénom</th>
                                        <th class="py-2 px-3">Catégorie</th>
                                        @foreach($cultesByDay as $dateKey => $cultesOfDay)
                                            <th class="py-2 px-2 text-center whitespace-nowrap" title="{{ $cultesOfDay->pluck('name')->join(', ') }}">
                                                {{ \Carbon\Carbon::parse($dateKey)->format('d/m') }}
                                            </th>
                                        @endforeach
                                        <th class="py-2 px-3 text-center">Total</th>
                                        <th class="py-2 px-3 text-center">Taux</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($presences as $i => $data)
                                        @php
                                            $taux = $data['taux_presence'];
                                            $tauxColor = $taux >= 75 ? 'text-green-600' : ($taux >= 50 ? 'text-orange-500' : 'text-red-600');
                                        @endphp
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="py-2 px-3 text-gray-400">{{ $i + 1 }}</td>
                                            <td class="py-2 px-3 font-medium whitespace-nowrap">{{ $data['member']->last_name }}</td>
                                            <td class="py-2 px-3 whitespace-nowrap">
                                                {{ $data['member']->first_name }}
                                                @if($data['member']->type === 'invite')
                                                    <span class="inline-block bg-orange-50 text-orange-700 text-xs px-1.5 rounded">Invité</span>
                                                @endif
                                            </td>
                                            <td class="py-2 px-3 text-gray-500">{{ $data['member']->category->name ?? '—' }}</td>
                                            @foreach($data['jours'] as $present)
                                                <td class="py-2 px-2 text-center font-bold {{ $present ? 'text-green-600' : 'text-red-400' }}">
                                                    {{ $present ? '✓' : '✗' }}
                                                </td>
                                            @endforeach
                                            <td class="py-2 px-3 text-center">
                                                <span class="font-semibold">{{ $data['nb_presences'] }}</span>
                                                <span class="text-gray-400">/ {{ $totalJours }}</span>
                                            </td>
                                            <td class="py-2 px-3 text-center font-semibold {{ $tauxColor }}">{{ $taux }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-50 font-semibold">
                                        <td colspan="4" class="py-2 px-3 text-right">Présents par jour</td>
                                        @foreach($presentsParJour as $nb)
                                            <td class="py-2 px-2 text-center">{{ $nb }}</td>
                                        @endforeach
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <div>
                <a href="{{ route('dashboard') }}">
                    <x-secondary-button type="button">Retour au tableau de bord</x-secondary-button>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
