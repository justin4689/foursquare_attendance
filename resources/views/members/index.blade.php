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
    </script>
</x-app-layout>
