<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Nouveaux membres — {{ $moisLabel }}
            </h2>
            @if($nouveaux->count() > 0)
            <a href="{{ route('dashboard.nouveaux-membres.pdf', ['mois' => $mois, 'annee' => $annee]) }}">
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
                    <form method="GET" action="{{ route('dashboard.nouveaux-membres') }}" class="flex flex-wrap gap-4 items-end">
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

            @if($nouveaux->count() === 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-500">
                    Aucun nouveau membre inscrit en {{ $moisLabel }}.
                </div>
            @else

                {{-- Statistiques --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $nouveaux->count() }}</div>
                        <div class="text-sm text-gray-500">Nouveaux membres</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $nouveaux->filter(fn($d) => $d['member']->type === 'permanent')->count() }}</div>
                        <div class="text-sm text-gray-500">Permanents</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                        <div class="text-2xl font-bold text-orange-500">{{ $nouveaux->filter(fn($d) => $d['member']->type === 'invite')->count() }}</div>
                        <div class="text-sm text-gray-500">Invités</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                        <div class="text-2xl font-bold text-gray-700">{{ $parCulte->count() }}</div>
                        <div class="text-sm text-gray-500">Cultes concernés</div>
                    </div>
                </div>

                {{-- Nouveaux membres par culte --}}
                @foreach($parCulte as $groupe)
                    @php $culte = $groupe->first()['premier_culte']; @endphp
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-semibold mb-4 text-purple-700">
                                {{ $culte->name }} — {{ $culte->date->format('d/m/Y') }}
                                @if($culte->heure) <span class="text-gray-400 text-sm">à {{ $culte->heure->format('H:i') }}</span> @endif
                                <span class="ml-2 inline-block bg-purple-100 text-purple-700 text-xs px-2 py-0.5 rounded-full">{{ $groupe->count() }} nouveau(x)</span>
                            </h3>
                            @include('dashboard.partials.nouveaux-membres-table', ['lignes' => $groupe])
                        </div>
                    </div>
                @endforeach

                @if($sansCulte->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-semibold mb-1 text-gray-700">
                                Inscrits sans présence enregistrée
                                <span class="ml-2 inline-block bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full">{{ $sansCulte->count() }}</span>
                            </h3>
                            <p class="text-sm text-gray-400 mb-4">Membres inscrits ce mois mais pas encore pointés présents à un culte.</p>
                            @include('dashboard.partials.nouveaux-membres-table', ['lignes' => $sansCulte])
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
