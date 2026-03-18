<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['members_count'] }}</div>
                    <div class="text-sm text-gray-500">{{ __('Membres') }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['cultes_count'] }}</div>
                    <div class="text-sm text-gray-500">{{ __('Cultes') }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-2xl font-bold text-purple-600">{{ $stats['categories_count'] }}</div>
                    <div class="text-sm text-gray-500">{{ __('Catégories') }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-lg font-bold text-gray-600">
                        @if($stats['last_culte'])
                            {{ $stats['last_culte']->date->format('d/m/Y') }}
                            @if($stats['last_culte']->heure)
                                {{ $stats['last_culte']->heure->format('H:i') }}
                            @endif
                        @else
                            —
                        @endif
                    </div>
                    <div class="text-sm text-gray-500">{{ __('Dernier culte') }}</div>
                </div>
            </div>

            <!-- Derniers cultes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Derniers cultes') }}</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left border-b">
                                    <th class="py-2 pr-4">{{ __('Nom') }}</th>
                                    <th class="py-2 pr-4">{{ __('Date') }}</th>
                                    <th class="py-2 pr-4">{{ __('Présences') }}</th>
                                    <th class="py-2 pr-4">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentCultes as $culte)
                                    <tr class="border-b">
                                        <td class="py-2 pr-4">{{ $culte->name }}</td>
                                        <td class="py-2 pr-4">
                                            {{ $culte->date->format('d/m/Y') }}
                                            @if($culte->heure)
                                                {{ $culte->heure->format('H:i') }}
                                            @endif
                                        </td>
                                        <td class="py-2 pr-4">{{ $culte->attendances_count }}</td>
                                        <td class="py-2 pr-4">
                                            <a class="underline" href="{{ route('cultes.show', $culte) }}">{{ __('Voir') }}</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="py-4" colspan="4">{{ __('Aucun culte.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
