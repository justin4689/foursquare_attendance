<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Rendez-vous — {{ $rendezVou->prenom }} {{ $rendezVou->nom }}
            </h2>
            <a href="{{ route('rendez-vous.index') }}"
               class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg text-sm">
                ← Retour à la liste
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Fiche du demandeur --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-base font-semibold text-gray-700 mb-4 border-b pb-2">Informations du demandeur</h3>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-6 text-sm">
                        <div>
                            <dt class="text-gray-500">Nom</dt>
                            <dd class="font-medium text-gray-900">{{ $rendezVou->nom }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Prénom</dt>
                            <dd class="font-medium text-gray-900">{{ $rendezVou->prenom }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Contact</dt>
                            <dd class="font-medium text-gray-900">{{ $rendezVou->contact }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Lieu d'habitation</dt>
                            <dd class="font-medium text-gray-900">{{ $rendezVou->lieu_habitation ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Motif</dt>
                            <dd class="font-medium text-gray-900">{{ $rendezVou->motif }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Pasteur souhaité</dt>
                            <dd class="font-medium text-gray-900">{{ $rendezVou->pasteur }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Date souhaitée</dt>
                            <dd class="font-medium text-gray-900">
                                {{ $rendezVou->date_souhaitee->format('d/m/Y') }}
                                @if($rendezVou->heure_souhaitee)
                                    à {{ \Illuminate\Support\Str::substr($rendezVou->heure_souhaitee, 0, 5) }}
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Demande reçue le</dt>
                            <dd class="font-medium text-gray-900">{{ $rendezVou->created_at->format('d/m/Y à H:i') }}</dd>
                        </div>
                        @if($rendezVou->message)
                            <div class="sm:col-span-2">
                                <dt class="text-gray-500">Message du demandeur</dt>
                                <dd class="font-medium text-gray-900 mt-1 p-3 bg-gray-50 rounded-md">{{ $rendezVou->message }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            {{-- Statut actuel --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-base font-semibold text-gray-700 mb-4 border-b pb-2">Statut actuel</h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $rendezVou->statutBadgeClass() }}">
                        {{ $rendezVou->statutLibelle() }}
                    </span>

                    @if($rendezVou->notes_admin)
                        <div class="mt-4">
                            <p class="text-sm text-gray-500 mb-1">Notes internes</p>
                            <p class="text-sm text-gray-800 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                                {{ $rendezVou->notes_admin }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Formulaire de mise à jour du statut --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-base font-semibold text-gray-700 mb-4 border-b pb-2">Mettre à jour le statut</h3>

                    <form method="POST" action="{{ route('rendez-vous.statut', $rendezVou) }}" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nouveau statut</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                @php
                                    $radioBorder = [
                                        'en_attente' => 'border-yellow-400 bg-yellow-50',
                                        'confirme'   => 'border-blue-400 bg-blue-50',
                                        'termine'    => 'border-green-400 bg-green-50',
                                        'annule'     => 'border-red-400 bg-red-50',
                                    ];
                                    $radioText = [
                                        'en_attente' => 'text-yellow-600',
                                        'confirme'   => 'text-blue-600',
                                        'termine'    => 'text-green-600',
                                        'annule'     => 'text-red-600',
                                    ];
                                @endphp
                                @foreach($statuts as $key => $label)
                                    <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer {{ $rendezVou->statut === $key ? ($radioBorder[$key] ?? 'border-gray-200') : 'border-gray-200 hover:border-gray-300' }}">
                                        <input type="radio" name="statut" value="{{ $key }}"
                                               {{ $rendezVou->statut === $key ? 'checked' : '' }}
                                               class="{{ $radioText[$key] ?? 'text-gray-600' }}">
                                        <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('statut')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes internes (visibles uniquement par l'équipe)</label>
                            <textarea name="notes_admin" rows="3" maxlength="1000"
                                      placeholder="Ex: RDV confirmé pour le samedi 10/05 à 10h00..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none">{{ old('notes_admin', $rendezVou->notes_admin) }}</textarea>
                            @error('notes_admin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-3">
                            <x-primary-button>
                                Enregistrer le statut
                            </x-primary-button>
                            <a href="{{ route('rendez-vous.index') }}">
                                <x-secondary-button type="button">Annuler</x-secondary-button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Suppression (admin seulement) --}}
            @if(auth()->user()->isAdmin())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-red-100">
                    <div class="p-6">
                        <h3 class="text-base font-semibold text-red-600 mb-3">Zone de danger</h3>
                        <form method="POST" action="{{ route('rendez-vous.destroy', $rendezVou) }}"
                              onsubmit="return confirm('Supprimer définitivement ce rendez-vous ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md text-sm">
                                Supprimer ce rendez-vous
                            </button>
                        </form>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
