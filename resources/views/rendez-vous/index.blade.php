<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Rendez-vous') }}
            </h2>
            <a href="{{ route('rendez-vous.create') }}" target="_blank"
               class="bg-[#185696] hover:bg-blue-800 text-white font-medium py-2 px-4 rounded-lg text-sm">
                + Nouvelle demande (public)
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Compteurs statut --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold text-yellow-700">{{ $counts['en_attente'] }}</p>
                    <p class="text-xs text-yellow-600 mt-1">En attente</p>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold text-blue-700">{{ $counts['confirme'] }}</p>
                    <p class="text-xs text-blue-600 mt-1">Confirmés</p>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold text-green-700">{{ $counts['termine'] }}</p>
                    <p class="text-xs text-green-600 mt-1">Terminés</p>
                </div>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold text-red-700">{{ $counts['annule'] }}</p>
                    <p class="text-xs text-red-600 mt-1">Annulés</p>
                </div>
            </div>

            {{-- Filtres --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4">
                    <form method="GET" action="{{ route('rendez-vous.index') }}" class="flex flex-wrap gap-3 items-end">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Statut</label>
                            <select name="statut" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Tous les statuts</option>
                                @foreach($statuts as $key => $label)
                                    <option value="{{ $key }}" {{ $statut === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Pasteur</label>
                            <select name="pasteur" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Tous les pasteurs</option>
                                @foreach($pasteurs as $p)
                                    <option value="{{ $p }}" {{ $pasteur === $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-md">
                            Filtrer
                        </button>
                        @if($statut || $pasteur)
                            <a href="{{ route('rendez-vous.index') }}" class="text-sm text-gray-500 hover:underline py-2">
                                Réinitialiser
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            {{-- Tableau --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left border-b text-gray-600">
                                    <th class="py-2 pr-4">Nom & Prénom</th>
                                    <th class="py-2 pr-4">Contact</th>
                                    <th class="py-2 pr-4">Motif</th>
                                    <th class="py-2 pr-4">Pasteur</th>
                                    <th class="py-2 pr-4">Date souhaitée</th>
                                    <th class="py-2 pr-4">Statut</th>
                                    <th class="py-2 pr-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rendezVous as $rdv)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-2 pr-4 font-medium">{{ $rdv->nom }} {{ $rdv->prenom }}</td>
                                        <td class="py-2 pr-4">{{ $rdv->contact }}</td>
                                        <td class="py-2 pr-4">{{ $rdv->motif }}</td>
                                        <td class="py-2 pr-4 text-xs">{{ $rdv->pasteur }}</td>
                                        <td class="py-2 pr-4">{{ $rdv->date_souhaitee->format('d/m/Y') }}
                                            @if($rdv->heure_souhaitee)
                                                <span class="text-gray-500">{{ \Illuminate\Support\Str::substr($rdv->heure_souhaitee, 0, 5) }}</span>
                                            @endif
                                        </td>
                                        <td class="py-2 pr-4">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $rdv->statutBadgeClass() }}">
                                                {{ $rdv->statutLibelle() }}
                                            </span>
                                        </td>
                                        <td class="py-2 pr-4">
                                            <a href="{{ route('rendez-vous.show', $rdv) }}" class="underline text-blue-600 hover:text-blue-800">Gérer</a>
                                            @if(auth()->user()->isAdmin())
                                                <span class="mx-1 text-gray-300">|</span>
                                                <form action="{{ route('rendez-vous.destroy', $rdv) }}" method="POST" class="inline"
                                                      onsubmit="return confirm('Supprimer ce rendez-vous ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="underline text-red-600 hover:text-red-800">Supprimer</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-8 text-center text-gray-400">Aucun rendez-vous trouvé.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($rendezVous->hasPages())
                        <div class="mt-4">
                            {{ $rendezVous->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
