<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Logs de Connexion des Agents') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtres -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('login-logs.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Filtre par agent -->
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Agent') }}
                            </label>
                            <select id="user_id" name="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">{{ __('Tous les agents') }}</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ request('user_id') == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtre par action -->
                        <div>
                            <label for="action" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Action') }}
                            </label>
                            <select id="action" name="action" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">{{ __('Toutes les actions') }}</option>
                                <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>
                                    {{ __('Connexion') }}
                                </option>
                                <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>
                                    {{ __('Déconnexion') }}
                                </option>
                            </select>
                        </div>

                        <!-- Filtre par date -->
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Date') }}
                            </label>
                            <input type="date" 
                                   id="date" 
                                   name="date" 
                                   value="{{ request('date') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Bouton de recherche -->
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                                {{ __('Filtrer') }}
                            </button>
                        </div>
                    </form>

                    @if(request()->hasAny(['user_id', 'action', 'date']))
                        <div class="mt-4">
                            <a href="{{ route('login-logs.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                {{ __('Réinitialiser les filtres') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tableau des logs -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left border-b">
                                    <th class="py-2 pr-4">{{ __('Agent') }}</th>
                                    <th class="py-2 pr-4">{{ __('Action') }}</th>
                                    <th class="py-2 pr-4">{{ __('Date et Heure') }}</th>
                                    <th class="py-2 pr-4">{{ __('Adresse IP') }}</th>
                                    <th class="py-2 pr-4">{{ __('Navigateur') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($loginLogs as $log)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-2 pr-4 font-medium">{{ $log->user->name }}</td>
                                        <td class="py-2 pr-4">
                                            <span class="px-2 py-1 text-xs rounded-full {{ $log->action === 'login' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $log->action === 'login' ? __('Connexion') : __('Déconnexion') }}
                                            </span>
                                        </td>
                                        <td class="py-2 pr-4">{{ $log->logged_at->format('d/m/Y H:i:s') }}</td>
                                        <td class="py-2 pr-4">{{ $log->ip_address ?? '—' }}</td>
                                        <td class="py-2 pr-4">
                                            <span class="truncate max-w-xs inline-block" title="{{ $log->user_agent ?? '—' }}">
                                                {{ $log->user_agent ? Str::limit($log->user_agent, 50) : '—' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="py-4 text-center" colspan="5">{{ __('Aucun log trouvé.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($loginLogs, 'links'))
                        <div class="mt-4">
                            {{ $loginLogs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
