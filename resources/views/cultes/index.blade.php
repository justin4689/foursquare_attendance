<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestion des Cultes') }}
            </h2>

            <a href="{{ route('cultes.create') }}">
                <x-primary-button type="button">
                    {{ __('Créer un culte') }}
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistiques rapides -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow">
                    <h3 class="text-sm font-medium text-gray-500">{{ __('Total cultes') }}</h3>
                    <p class="text-2xl font-bold text-[#185696]">{{ method_exists($cultes, 'total') ? $cultes->total() : $cultes->count() }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <h3 class="text-sm font-medium text-gray-500">{{ __('Cultes ce mois') }}</h3>
                    <p class="text-2xl font-bold text-green-600">{{ \App\Models\Culte::whereMonth('date', now()->month)->count() }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <h3 class="text-sm font-medium text-gray-500">{{ __('À venir') }}</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ \App\Models\Culte::AVenir()->count() }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <h3 class="text-sm font-medium text-gray-500">{{ __('Dernier culte') }}</h3>
                    <p class="text-sm font-bold text-gray-800">@if($cultes->first()) {{ $cultes->first()->date->format('d/m/Y') }} @else {{ __('Aucun') }} @endif</p>
                </div>
            </div>

            <!-- Tableau des cultes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left border-b">
                                    <th class="py-2 pr-4">{{ __('Nom du culte') }}</th>
                                    <th class="py-2 pr-4">{{ __('Date') }}</th>
                                    <th class="py-2 pr-4">{{ __('Heure') }}</th>
                                    <th class="py-2 pr-4">{{ __('Jour') }}</th>
                                    <th class="py-2 pr-4">{{ __('Présents') }}</th>
                                    <th class="py-2 pr-4">{{ __('Statut') }}</th>
                                    <th class="py-2 pr-4">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cultes as $culte)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-2 pr-4 font-medium">{{ $culte->name }}</td>
                                        <td class="py-2 pr-4">{{ $culte->date->format('d/m/Y') }}</td>
                                        <td class="py-2 pr-4">
                                            @if($culte->heure)
                                                {{ $culte->heure->format('H:i') }}
                                                @if($culte->fin)
                                                    - {{ $culte->fin->format('H:i') }}
                                                @endif
                                            @else
                                                {{ __('Non défini') }}
                                            @endif
                                        </td>
                                        <td class="py-2 pr-4">{{ \Carbon\Carbon::parse($culte->date)->locale('fr')->format('l') }}</td>
                                        <td class="py-2 pr-4">
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">
                                                {{ $culte->nb_presences }}
                                            </span>
                                        </td>
                                        <td class="py-2 pr-4">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium">
                                                {{ $culte->statut_libelle }}
                                            </span>
                                        </td>
                                        <td class="py-2 pr-4">
                                            <div class="flex gap-2">
                                                <a class="text-gray-600 hover:text-gray-800 underline" href="{{ route('cultes.show', $culte) }}">{{ __('Voir') }}</a>
                                                @if(!in_array($culte->statut, ['en_cours', 'passé']))
                                                    <a class="text-gray-600 hover:text-gray-800 underline" href="{{ route('cultes.edit', $culte) }}">{{ __('Modifier') }}</a>
                                                @endif
                                                @if(!in_array($culte->statut, ['en_cours']))
                                                    @if(!auth()->user()->isAgent())
                                                        <form action="{{ route('cultes.destroy', $culte) }}" method="POST" class="inline" onsubmit="return confirmDeleteCulte('{{ $culte->name }}', {{ $culte->attendances()->count() }})">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="underline">{{ __('Supprimer') }}</button>
                                                        </form>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="py-4 text-center" colspan="7">{{ __('Aucun culte trouvé.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($cultes, 'links'))
                        <div class="mt-4">
                            {{ $cultes->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fonction de confirmation de suppression de culte
        function confirmDeleteCulte(culteName, attendancesCount) {
            if (attendancesCount > 0) {
                return confirm(`ATTENTION : Ce culte a ${attendancesCount} enregistrement(s) de présence !\n\nÊtes-vous sûr de vouloir supprimer le culte "${culteName}" ET TOUTES ses présences ?\n\nCette action est irréversible.`);
            } else {
                return confirm(`Êtes-vous sûr de vouloir supprimer le culte "${culteName}" ?\n\nCette action est irréversible.`);
            }
        }
    </script>
</x-app-layout>
