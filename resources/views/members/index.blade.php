<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestion des Membres') }}
            </h2>

            <a href="{{ route('members.create') }}">
                <x-primary-button type="button">
                    {{ __('Ajouter un membre') }}
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
                                    <th class="py-2 pr-4">{{ __('Prénom') }}</th>
                                    <th class="py-2 pr-4">{{ __('Catégorie') }}</th>
                                    <th class="py-2 pr-4">{{ __('Contact') }}</th>
                                    <th class="py-2 pr-4">{{ __('Type') }}</th>
                                    <th class="py-2 pr-4">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($members as $member)
                                    <tr class="border-b">
                                        <td class="py-2 pr-4">{{ $member->last_name }}</td>
                                        <td class="py-2 pr-4">{{ $member->first_name }}</td>
                                        <td class="py-2 pr-4">{{ $member->category->name ?? '—' }}</td>
                                        <td class="py-2 pr-4">{{ $member->phone ?? '—' }}</td>
                                        <td class="py-2 pr-4">
                                            @if($member->type == 'permanent')
                                                <span class="bg-green-100 text-green-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-green-200 dark:text-green-900">Permanent</span>
                                            @elseif($member->type == 'invite')
                                                <span class="bg-orange-100 text-orange-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-orange-200 dark:text-orange-900">Invite</span>
                                            @endif
                                        </td>
                                        <td class="py-2 pr-4">
                                            <a class="underline" href="{{ route('members.show', $member) }}">{{ __('Voir') }}</a>
                                            <span class="mx-1">|</span>
                                            <a class="underline" href="{{ route('members.edit', $member) }}">{{ __('Modifier') }}</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="py-4" colspan="5">{{ __('Aucun membre.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($members, 'links'))
                        <div class="mt-4">
                            {{ $members->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
