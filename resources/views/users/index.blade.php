<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestion des Utilisateurs') }}
            </h2>

            <a href="{{ route('users.create') }}">
                <x-primary-button type="button">
                    {{ __('Ajouter un utilisateur') }}
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left border-b">
                                    <th class="py-2 pr-4">{{ __('Nom') }}</th>
                                    <th class="py-2 pr-4">{{ __('Email') }}</th>
                                    <th class="py-2 pr-4">{{ __('Rôle') }}</th>
                                    <th class="py-2 pr-4">{{ __('Date de création') }}</th>
                                    <th class="py-2 pr-4">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 pr-4">{{ $user->name }}</td>
                                        <td class="py-3 pr-4">{{ $user->email }}</td>
                                        <td class="py-3 pr-4">
                                            <span class="px-2 py-1 text-xs rounded-full {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="py-3 pr-4">{{ $user->created_at->format('d/m/Y') }}</td>
                                        <td class="py-3 pr-4">
                                            <div class="flex gap-2">
                                                <a href="{{ route('users.show', $user) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                                    {{ __('Voir') }}
                                                </a>
                                                <a href="{{ route('users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                                                    {{ __('Modifier') }}
                                                </a>
                                                @if ($user->id !== auth()->id() && !auth()->user()->isAgent())
                                                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                            {{ __('Supprimer') }}
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-8 text-gray-500">
                                            {{ __('Aucun utilisateur trouvé.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
