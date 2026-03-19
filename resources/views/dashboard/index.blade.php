<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['members_count'] }}</div>
                    <div class="text-sm text-gray-500">{{ __('Membres') }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['permanent_members_count'] }}</div>
                    <div class="text-sm text-gray-500">{{ __('Permanents') }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-2xl font-bold text-orange-600">{{ $stats['invite_members_count'] }}</div>
                    <div class="text-sm text-gray-500">{{ __('Invités') }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-2xl font-bold text-purple-600">{{ $stats['cultes_count'] }}</div>
                    <div class="text-sm text-gray-500">{{ __('Cultes') }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-2xl font-bold text-indigo-600">{{ $stats['categories_count'] }}</div>
                    <div class="text-sm text-gray-500">{{ __('Catégories') }}</div>
                </div>
            </div>

            <!-- Ratio Permanents vs Invités -->
            @if($stats['members_count'] > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Répartition des membres') }}</h3>
                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ __('Permanents') }}</span>
                                <span class="font-medium">{{ $stats['permanent_members_count'] }} ({{ round($stats['permanent_members_count'] / $stats['members_count'] * 100, 1) }}%)</span>
                            </div>
                            <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $stats['permanent_members_count'] / $stats['members_count'] * 100 }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ __('Invités') }}</span>
                                <span class="font-medium">{{ $stats['invite_members_count'] }} ({{ round($stats['invite_members_count'] / $stats['members_count'] * 100, 1) }}%)</span>
                            </div>
                            <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-orange-600 h-2 rounded-full" style="width: {{ $stats['invite_members_count'] / $stats['members_count'] * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

          
                               
            <!-- Dernier culte -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-lg font-bold text-gray-600">
                    @if($stats['last_culte'])
                        {{ $stats['last_culte']->date->format('d/m/Y') }}
                        @if($stats['last_culte']->heure)
                            {{ $stats['last_culte']->heure->format('H:i') }}
                        @endif
                        @if($stats['last_culte']->fin)
                            - {{ $stats['last_culte']->fin->format('H:i') }}
                        @endif
                    @else
                        —
                    @endif
                </div>
                <div class="text-sm text-gray-500">{{ __('Dernier culte') }}</div>
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
