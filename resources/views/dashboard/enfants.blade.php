<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Liste des enfants
            </h2>
            @if($enfants->count() > 0)
            <a href="{{ route('dashboard.enfants.pdf') }}">
                <x-primary-button type="button" style="background-color: rgb(34 197 94) !important; outline-color: rgb(34 197 94) !important;">
                    Exporter en PDF
                </x-primary-button>
            </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(!$categorieTrouvee)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-500">
                    Aucune catégorie « Enfant » n'existe. Crée-la dans la gestion des catégories pour alimenter cette liste.
                </div>
            @elseif($enfants->count() === 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-500">
                    Aucun enfant enregistré pour le moment.
                </div>
            @else

                {{-- Statistiques --}}
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $enfants->count() }}</div>
                        <div class="text-sm text-gray-500">Enfants</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $enfants->filter(fn($d) => $d['member']->type === 'permanent')->count() }}</div>
                        <div class="text-sm text-gray-500">Permanents</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                        <div class="text-2xl font-bold text-orange-500">{{ $enfants->filter(fn($d) => $d['member']->type === 'invite')->count() }}</div>
                        <div class="text-sm text-gray-500">Invités</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="text-left border-b bg-gray-50">
                                        <th class="py-2 px-3">N°</th>
                                        <th class="py-2 px-3">Nom</th>
                                        <th class="py-2 px-3">Prénom</th>
                                        <th class="py-2 px-3">Type</th>
                                        <th class="py-2 px-3">Contact</th>
                                        <th class="py-2 px-3">Lieu d'habitation</th>
                                        <th class="py-2 px-3">Anniversaire</th>
                                        <th class="py-2 px-3">Inscrit le</th>
                                        <th class="py-2 px-3 text-center">Présences</th>
                                        <th class="py-2 px-3">Dernière présence</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($enfants as $i => $data)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="py-2 px-3 text-gray-400">{{ $i + 1 }}</td>
                                            <td class="py-2 px-3 font-medium">{{ $data['member']->last_name }}</td>
                                            <td class="py-2 px-3">{{ $data['member']->first_name }}</td>
                                            <td class="py-2 px-3">
                                                @if($data['member']->type === 'permanent')
                                                    <span class="inline-block bg-green-50 text-green-700 text-xs px-2 py-0.5 rounded">Permanent</span>
                                                @else
                                                    <span class="inline-block bg-orange-50 text-orange-700 text-xs px-2 py-0.5 rounded">Invité</span>
                                                @endif
                                            </td>
                                            <td class="py-2 px-3 text-gray-500 whitespace-nowrap">{{ $data['member']->phone ?? '—' }}</td>
                                            <td class="py-2 px-3 text-gray-500">{{ $data['member']->lieu_habitation ?? '—' }}</td>
                                            <td class="py-2 px-3 text-gray-500">{{ $data['member']->anniversaire_jour_mois ?? '—' }}</td>
                                            <td class="py-2 px-3 text-gray-500 whitespace-nowrap">{{ $data['date_inscription'] }}</td>
                                            <td class="py-2 px-3 text-center font-semibold">{{ $data['nb_presences'] }}</td>
                                            <td class="py-2 px-3 text-gray-500">{{ $data['derniere_presence'] ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
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
