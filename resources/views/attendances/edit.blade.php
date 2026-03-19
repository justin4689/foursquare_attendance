<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pointage') }} — {{ $culte->name }} ({{ $culte->date->format('d/m/Y') }} {{ $culte->heure->format('H:i') }} - {{ $culte->fin->format('H:i') }})
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('cultes.presence.update', $culte) }}" class="space-y-4">
                        @csrf

                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="text-left border-b">
                                        <th class="py-2 pr-4">{{ __('Membre') }}</th>
                                        <th class="py-2 pr-4">{{ __('Présence') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($members as $member)
                                        @php
                                            $current = $attendanceByMemberId[$member->id] ?? null;
                                            $status = is_object($current) ? (int) $current->status : (is_null($current) ? null : (int) $current);
                                            $presentChecked = $status === 1;
                                            $absentChecked = $status === 0 || is_null($status);
                                        @endphp

                                        <tr class="border-b">
                                            <td class="py-2 pr-4">
                                                {{ $member->first_name }} {{ $member->last_name }}
                                            </td>
                                            <td class="py-2 pr-4">
                                                <div class="flex gap-6">
                                                    <label class="inline-flex items-center gap-2">
                                                        <input
                                                            type="radio"
                                                            name="status[{{ $member->id }}]"
                                                            value="1"
                                                            class="border-gray-300 focus:ring-[#185696]"
                                                            @checked($presentChecked)
                                                        />
                                                        <span>{{ __('Présent') }}</span>
                                                    </label>

                                                    <label class="inline-flex items-center gap-2">
                                                        <input
                                                            type="radio"
                                                            name="status[{{ $member->id }}]"
                                                            value="0"
                                                            class="border-gray-300 focus:ring-[#185696]"
                                                            @checked($absentChecked)
                                                        />
                                                        <span>{{ __('Absent') }}</span>
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex gap-2">
                            <x-primary-button>
                                {{ __('Enregistrer') }}
                            </x-primary-button>

                            <a href="{{ route('cultes.show', $culte) }}">
                                <x-secondary-button type="button">
                                    {{ __('Retour') }}
                                </x-secondary-button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
