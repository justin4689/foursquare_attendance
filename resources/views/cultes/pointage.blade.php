<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pointage - ') . $culte->name }}
        </h2>
    </x-slot>

    

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Infos du culte -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold">{{ $culte->name }}</h3>
                            <p class="text-sm text-gray-600">
                                {{ $culte->date->format('d/m/Y') }}
                                @if($culte->heure)
                                    - {{ $culte->heure->format('H:i') }}
                                    @if($culte->fin)
                                        à {{ $culte->fin->format('H:i') }}
                                    @endif
                                @endif
                            </p>
                        </div>
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $culte->statut === 'en_cours' ? 'bg-yellow-100 text-yellow-800' : ($culte->statut === 'aujourd_hui' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                {{ $culte->statut_libelle }}
                            </span>
                        </div>
                    </div>

                    <!-- Statistiques rapides -->
                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-green-600">{{ $culte->nb_presences }}</p>
                            <p class="text-sm text-gray-600">{{ __('Présents') }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-red-600">{{ $culte->nb_absences }}</p>
                            <p class="text-sm text-gray-600">{{ __('Absents') }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600">{{ $culte->attendances()->count() }}</p>
                            <p class="text-sm text-gray-600">{{ __('Total pointés') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire de pointage rapide -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Pointage rapide') }}</h3>
                    
                    <form method="POST" action="{{ route('attendance.pointage') }}" class="space-y-4">
                        @csrf
                        
                        <!-- Culte sélectionné (caché) -->
                        <input type="hidden" name="culte_id" value="{{ $culte->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="member_search" :value="__('Rechercher un membre')" />
                                <x-text-input id="member_search" name="member_search" type="text" class="mt-1 block w-full" placeholder="Nom ou email du membre" required />
                                <x-input-error :messages="$errors->get('member_search')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Statut de présence')" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-[#185696] focus:ring-[#185696] rounded-md shadow-sm" required>
                                    <option value="1">{{ __('Présent') }}</option>
                                    <option value="0">{{ __('Absent') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <x-primary-button>
                                {{ __('Pointer') }}
                            </x-primary-button>

                            <a href="{{ route('cultes.show', $culte) }}">
                                <x-secondary-button type="button">
                                    {{ __('Voir les détails') }}
                                </x-secondary-button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
