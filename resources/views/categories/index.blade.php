<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestion des Catégories') }}
            </h2>

            <a href="{{ route('categories.create') }}">
                <x-primary-button type="button">
                    {{ __('Ajouter une catégorie') }}
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left border-b">
                                    <th class="py-2 pr-4">{{ __('Nom') }}</th>
                                    <th class="py-2 pr-4">{{ __('Description') }}</th>
                                    <th class="py-2 pr-4">{{ __('Membres') }}</th>
                                    <th class="py-2 pr-4">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr class="border-b">
                                        <td class="py-2 pr-4">{{ $category->name }}</td>
                                        <td class="py-2 pr-4">{{ $category->description ?? '—' }}</td>
                                        <td class="py-2 pr-4 mr-2">{{ $category->members_count ?? 0 }}</td>
                                        <td class="py-2 pr-4">
                                            <a class="underline" href="{{ route('categories.show', $category) }}">{{ __('Voir') }}</a>
                                            <span class="mx-1">|</span>
                                            <a class="underline" href="{{ route('categories.edit', $category) }}">{{ __('Modifier') }}</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="py-4" colspan="4">{{ __('Aucune catégorie.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($categories, 'links'))
                        <div class="mt-4">
                            {{ $categories->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
