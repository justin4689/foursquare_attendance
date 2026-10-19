<div class="overflow-x-auto">
    <table class="min-w-full text-sm">
        <thead>
            <tr class="text-left border-b bg-gray-50">
                <th class="py-2 px-3">N°</th>
                <th class="py-2 px-3">Nom</th>
                <th class="py-2 px-3">Prénom</th>
                <th class="py-2 px-3">Type</th>
                <th class="py-2 px-3">Catégorie</th>
                <th class="py-2 px-3">Contact</th>
                <th class="py-2 px-3">Lieu d'habitation</th>
                <th class="py-2 px-3">Inscrit le</th>
                <th class="py-2 px-3 text-center">Présences</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lignes->values() as $i => $data)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2 px-3 text-gray-400">{{ $i + 1 }}</td>
                    <td class="py-2 px-3 font-medium">{{ $data['member']->last_name }}</td>
                    <td class="py-2 px-3">{{ $data['member']->first_name }}</td>
                    <td class="py-2 px-3">
                        @if($data['member']->type === 'permanent')
                            <span class="inline-block bg-green-50 text-green-700 text-xs px-2 py-0.5 rounded">Permanent</span>
                        @else
                            <span class="inline-block bg-orange-50 text-orange-700 text-xs px-2 py-0.5 rounded">Invité</span>
                        @endif
                    </td>
                    <td class="py-2 px-3 text-gray-500">{{ $data['member']->category->name ?? '—' }}</td>
                    <td class="py-2 px-3 text-gray-500">{{ $data['member']->phone ?? '—' }}</td>
                    <td class="py-2 px-3 text-gray-500">{{ $data['member']->lieu_habitation ?? '—' }}</td>
                    <td class="py-2 px-3 text-gray-500">{{ $data['date_inscription'] }}</td>
                    <td class="py-2 px-3 text-center font-semibold">{{ $data['nb_presences'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
