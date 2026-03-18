<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pointage Intelligent') }}
        </h2>
    </x-slot>

    

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Détection automatique du culte -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 mr-3">
                            🎯 {{ __('Recommandation') }}
                        </span>
                        {{ __('Culte suggéré automatiquement') }}
                    </h3>

                    @if($culteActuel)
                        <!-- Culte en cours -->
                        <div class="border-l-4 border-yellow-500 bg-yellow-50 p-4 rounded">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold text-yellow-800">{{ $culteActuel->name }}</h4>
                                    <p class="text-yellow-600 text-sm">
                                        {{ $culteActuel->date->format('d/m/Y') }} - 
                                        {{ $culteActuel->heure?->format('H:i') }} 
                                        @if($culteActuel->fin) à {{ $culteActuel->fin->format('H:i') }} @endif
                                    </p>
                                    <p class="text-yellow-700 font-medium mt-1">🟡 {{ __('En cours maintenant') }}</p>
                                </div>
                              
                            </div>
                        </div>

                    @elseif($prochainCulteAujourdhui)
                        <!-- Prochain culte aujourd'hui -->
                        <div class="border-l-4 border-blue-500 bg-blue-50 p-4 rounded">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold text-blue-800">{{ $prochainCulteAujourdhui->name }}</h4>
                                    <p class="text-blue-600 text-sm">
                                        {{ $prochainCulteAujourdhui->date->format('d/m/Y') }} - 
                                        {{ $prochainCulteAujourdhui->heure?->format('H:i') }}
                                        @if($prochainCulteAujourdhui->fin) à {{ $prochainCulteAujourdhui->fin->format('H:i') }} @endif
                                    </p>
                                    <p class="text-blue-700 font-medium mt-1">
                                        🔵 {{ __("Commence dans") }} {{ $prochainCulteAujourdhui->heure->diff(now())->format('%h h %i min') }}
                                    </p>
                                </div>
                              
                            </div>
                        </div>

                    @else
                        <!-- Aucun culte aujourd'hui -->
                        <div class="border-l-4 border-gray-500 bg-gray-50 p-4 rounded">
                            <div class="text-center">
                                <h4 class="font-semibold text-gray-800">{{ __('Aucun culte aujourd\'hui') }}</h4>
                                <p class="text-gray-600 text-sm mt-2">{{ __('Revenez plus tard ou pointez sur un autre culte') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Formulaire de pointage rapide -->
            @if($culteActuel || $prochainCulteAujourdhui)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Pointage Rapide') }}</h3>
                        
                        <form method="POST" action="{{ route('attendance.pointage') }}" class="space-y-4">
                            @csrf
                            
                            <!-- Culte sélectionné automatiquement -->
                            <input type="hidden" name="culte_id" value="{{ $culteActuel?->id ?? $prochainCulteAujourdhui?->id }}">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="member_search" :value="__('Rechercher un membre')" />
                                    <x-text-input id="member_search" name="member_search" type="text" class="mt-1 block w-full" placeholder="Nom, prénom ou email du membre" required autofocus />
                                    <x-input-error :messages="$errors->get('member_search')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="status" :value="__('Statut')" />
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

                                <button type="button" onclick="window.location.href='{{ route('cultes.index') }}'" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition-colors">
                                    {{ __('Voir tous les cultes') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Autres options -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Autres Options') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Prochains cultes -->
                        <div>
                            <h4 class="font-medium text-gray-700 mb-3">{{ __('Prochains cultes')}}</h4>
                            @if($prochainsCultes->count() > 0)
                                <div class="space-y-2">
                                    @foreach($prochainsCultes->take(3) as $culte)
                                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                            <div>
                                                <p class="font-medium text-sm">{{ $culte->name }}</p>
                                                <p class="text-xs text-gray-600">
                                                    {{ $culte->date->format('d/m') }} à {{ $culte->heure?->format('H:i') }}
                                                </p>
                                            </div>
                                            <a href="{{ route('cultes.pointage', $culte) }}" class="text-blue-600 hover:text-blue-800 text-sm underline">
                                                {{ __('Pointer') }}
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500">{{ __('Aucun culte à venir') }}</p>
                            @endif
                        </div>

                        <!-- Actions rapides -->
                        <div>
                            <h4 class="font-medium text-gray-700 mb-3">{{ __('Actions Rapides') }}</h4>
                            <div class="space-y-2">
                                <a href="{{ route('cultes.index') }}" class="block w-full text-left px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded text-sm">
                                    📋 {{ __('Voir tous les cultes') }}
                                </a>
                                <a href="{{ route('cultes.create') }}" class="block w-full text-left px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded text-sm">
                                    ➕ {{ __('Créer un culte') }}
                                </a>
                                <a href="{{ route('dashboard') }}" class="block w-full text-left px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded text-sm">
                                    📊 {{ __('Tableau de bord') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
