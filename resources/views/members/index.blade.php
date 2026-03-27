<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestion des Membres') }}
            </h2>

            <div class="flex gap-3">
                <button onclick="exportToPDF()" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg text-sm">
                    {{ __('Exporter en PDF') }}
                </button>
                <a href="{{ route('members.create') }}">
                    <x-primary-button type="button">
                        {{ __('Ajouter un membre') }}
                    </x-primary-button>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Champ de recherche -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-4">
                    <form method="GET" action="{{ route('members.index') }}" class="flex gap-3">
                        <div class="flex-1 relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request()->get('search', '') }}" 
                                   placeholder="{{ __('Rechercher par nom, prénom ou téléphone...') }}" 
                                   class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   id="search-input">
                            @if(request()->get('search'))
                                <a href="{{ route('members.index') }}" 
                                   class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 text-xl font-bold leading-none"
                                   title="{{ __('Effacer la recherche') }}">
                                    ×
                                </a>
                            @endif
                        </div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                            {{ __('Rechercher') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Tabs -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px px-3 flex space-x-8" aria-label="Tabs">
                        <button onclick="showTab('permanents')" id="tab-permanents" class="tab-button border-b-2 border-blue-500 py-2 px-1 text-sm font-medium text-blue-600">
                            Permanents ({{ $permanentsCount }})
                        </button>
                        <button onclick="showTab('invites')" id="tab-invites" class="tab-button border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Invités ({{ $invitesCount }})
                        </button>
                    </nav>
                </div>

                <!-- Tab: Permanents -->
                <div id="content-permanents" class="tab-content p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left border-b">
                                    <th class="py-2 pr-4">{{ __('Nom') }}</th>
                                    <th class="py-2 pr-4">{{ __('Prénom') }}</th>
                                    <th class="py-2 pr-4">{{ __('Catégorie') }}</th>
                                    <th class="py-2 pr-4">{{ __('Contact') }}</th>
                                    <th class="py-2 pr-4">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permanents as $member)
                                    <tr class="border-b">
                                        <td class="py-2 pr-4">{{ $member->last_name }}</td>
                                        <td class="py-2 pr-4">{{ $member->first_name }}</td>
                                        <td class="py-2 pr-4">{{ $member->category->name ?? '—' }}</td>
                                        <td class="py-2 pr-4">{{ $member->phone ?? '—' }}</td>
                                        <td class="py-2 pr-4">
                                            <a class="underline" href="{{ route('members.show', $member) }}">{{ __('Voir') }}</a>
                                            <span class="mx-1">|</span>
                                            <a class="underline" href="{{ route('members.edit', $member) }}">{{ __('Modifier') }}</a>
                                            <span class="mx-1">|</span>
                                            @if(!auth()->user()->isAgent())
                                                <form action="{{ route('members.destroy', $member) }}" method="POST" class="inline" onsubmit="return confirmDeleteMember('{{ $member->first_name }} {{ $member->last_name }}', {{ $member->attendances()->count() }})">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="underline ">{{ __('Supprimer') }}</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="py-4" colspan="4">{{ __('Aucun membre permanent.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination pour les permanents --}}
                    @if(method_exists($permanents, 'links'))
                        <div class="mt-4">
                            {{ $permanents->links() }}
                        </div>
                    @endif
                </div>

                <!-- Tab: Invités -->
                <div id="content-invites" class="tab-content p-6 hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left border-b">
                                    <th class="py-2 pr-4">{{ __('Nom') }}</th>
                                    <th class="py-2 pr-4">{{ __('Prénom') }}</th>
                                    <th class="py-2 pr-4">{{ __('Catégorie') }}</th>
                                    <th class="py-2 pr-4">{{ __('Contact') }}</th>
                                    <th class="py-2 pr-4">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invites as $member)
                                    <tr class="border-b">
                                        <td class="py-2 pr-4">{{ $member->last_name }}</td>
                                        <td class="py-2 pr-4">{{ $member->first_name }}</td>
                                        <td class="py-2 pr-4">{{ $member->category->name ?? '—' }}</td>
                                        <td class="py-2 pr-4">{{ $member->phone ?? '—' }}</td>
                                        <td class="py-2 pr-4">
                                            <a class="underline" href="{{ route('members.show', $member) }}">{{ __('Voir') }}</a>
                                            <span class="mx-1">|</span>
                                            <a class="underline" href="{{ route('members.edit', $member) }}">{{ __('Modifier') }}</a>
                                             <span class="mx-1">|</span>
                                            @if(!auth()->user()->isAgent())
                                                <form action="{{ route('members.destroy', $member) }}" method="POST" class="inline" onsubmit="return confirmDeleteMember('{{ $member->first_name }} {{ $member->last_name }}', {{ $member->attendances()->count() }})">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="underline ">{{ __('Supprimer') }}</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="py-4" colspan="4">{{ __('Aucun invité.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination pour les invités --}}
                    @if(method_exists($invites, 'links'))
                        <div class="mt-4">
                            {{ $invites->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <script>
        let currentTab = 'permanents';
        
        function showTab(tabName) {
            currentTab = tabName;
            
            // Cacher tous les contenus
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Réinitialiser tous les boutons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Afficher le contenu sélectionné
            document.getElementById('content-' + tabName).classList.remove('hidden');
            
            // Activer le bouton sélectionné
            const activeButton = document.getElementById('tab-' + tabName);
            activeButton.classList.remove('border-transparent', 'text-gray-500');
            activeButton.classList.add('border-blue-500', 'text-blue-600');
        }
        
        function exportToPDF() {
            const url = `{{ route('members.export.pdf') }}?type=${currentTab}`;
            window.open(url, '_blank');
        }
        
        // Fonction de recherche automatique
        function performSearch() {
            const searchInput = document.getElementById('search-input');
            const form = searchInput.closest('form');
            
            // Créer une nouvelle URL avec les paramètres actuels
            const url = new URL(form.action);
            url.searchParams.set('search', searchInput.value);
            
            // Rediriger vers la nouvelle URL
            window.location.href = url.toString();
        }
        
        // Écouter les changements dans le champ de recherche avec délai
        let searchTimeout;
        document.getElementById('search-input').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performSearch();
            }, 500); // Attendre 500ms après la fin de la frappe
        });
        
        // Écouter la touche Entrée pour recherche immédiate
        document.getElementById('search-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchTimeout);
                performSearch();
            }
        });
        
        // Fonction de confirmation de suppression de membre
        function confirmDeleteMember(memberName, attendancesCount) {
            if (attendancesCount > 0) {
                return confirm(`ATTENTION : Ce membre a ${attendancesCount} enregistrement(s) de présence !\n\nÊtes-vous sûr de vouloir supprimer le membre "${memberName}" ET TOUTES ses présences ?\n\nCette action est irréversible.`);
            } else {
                return confirm(`Êtes-vous sûr de vouloir supprimer le membre "${memberName}" ?\n\nCette action est irréversible.`);
            }
        }
    </script>
</x-app-layout>
