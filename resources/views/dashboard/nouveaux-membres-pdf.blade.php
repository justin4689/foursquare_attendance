<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nouveaux Membres — {{ $moisLabel }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 20px;
            padding-bottom: 70px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .logo {
            width: 70px;
            height: auto;
            margin-bottom: 8px;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            margin: 8px 0 4px;
        }
        .subtitle {
            font-size: 12px;
            color: #555;
        }
        .stats-row {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px;
            margin: 15px 0;
        }
        .stat-card {
            border: 1px solid #e0e0e0;
            background: #f9f9f9;
            border-radius: 6px;
            padding: 10px;
            text-align: center;
        }
        .stat-number {
            font-size: 18px;
            font-weight: bold;
        }
        .stat-label {
            font-size: 10px;
            color: #666;
            margin-top: 2px;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin: 20px 0 8px;
            color: #333;
            border-bottom: 1px solid #ccc;
            padding-bottom: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
            padding: 6px 8px;
            border: 1px solid #ccc;
            text-align: left;
            font-size: 10px;
        }
        td {
            padding: 5px 8px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        tr:nth-child(even) td {
            background-color: #fafafa;
        }
        .text-center { text-align: center; }
        .text-purple { color: #7e3af2; font-weight: bold; }
        .text-green  { color: #27ae60; font-weight: bold; }
        .text-orange { color: #e67e22; font-weight: bold; }
        .footer {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            margin: 0 20px;
            border-top: 1px solid #ccc;
            padding-top: 6px;
            text-align: center;
            font-size: 9px;
            color: #999;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('images/logo2.jpeg') }}" alt="Logo" class="logo" onerror="this.style.display='none'">
        <div class="title">Rapport Mensuel des Nouveaux Membres</div>
        <div class="subtitle">{{ $moisLabel }}</div>
        <div class="subtitle">Généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</div>
    </div>

    {{-- Statistiques --}}
    <table class="stats-row">
        <tr>
            <td class="stat-card">
                <div class="stat-number text-purple">{{ $nouveaux->count() }}</div>
                <div class="stat-label">Nouveaux membres</div>
            </td>
            <td class="stat-card">
                <div class="stat-number text-green">{{ $nouveaux->filter(fn($d) => $d['member']->type === 'permanent')->count() }}</div>
                <div class="stat-label">Permanents</div>
            </td>
            <td class="stat-card">
                <div class="stat-number text-orange">{{ $nouveaux->filter(fn($d) => $d['member']->type === 'invite')->count() }}</div>
                <div class="stat-label">Invités</div>
            </td>
            <td class="stat-card">
                <div class="stat-number">{{ $parCulte->count() }}</div>
                <div class="stat-label">Cultes concernés</div>
            </td>
        </tr>
    </table>

    @php
        $sections = $parCulte->map(function ($groupe) {
            $culte = $groupe->first()['premier_culte'];
            return [
                'titre'  => $culte->name . ' — ' . $culte->date->format('d/m/Y') . ($culte->heure ? ' à ' . $culte->heure->format('H:i') : ''),
                'lignes' => $groupe->values(),
            ];
        })->values();

        if ($sansCulte->count() > 0) {
            $sections->push(['titre' => 'Inscrits sans présence enregistrée', 'lignes' => $sansCulte]);
        }
    @endphp

    @forelse($sections as $section)
        <div class="section-title">{{ $section['titre'] }} ({{ $section['lignes']->count() }})</div>
        <table>
            <thead>
                <tr>
                    <th class="text-center">N°</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Type</th>
                    <th>Catégorie</th>
                    <th>Contact</th>
                    <th>Lieu d'habitation</th>
                    <th>Inscrit le</th>
                    <th class="text-center">Présences</th>
                </tr>
            </thead>
            <tbody>
                @foreach($section['lignes'] as $i => $data)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>{{ $data['member']->last_name }}</td>
                        <td>{{ $data['member']->first_name }}</td>
                        <td>{{ $data['member']->type === 'permanent' ? 'Permanent' : 'Invité' }}</td>
                        <td>{{ $data['member']->category->name ?? 'NC' }}</td>
                        <td>{{ $data['member']->phone ?? '—' }}</td>
                        <td>{{ $data['member']->lieu_habitation ?? '—' }}</td>
                        <td>{{ $data['date_inscription'] }}</td>
                        <td class="text-center">{{ $data['nb_presences'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @empty
        <p>Aucun nouveau membre inscrit en {{ $moisLabel }}.</p>
    @endforelse

    <div class="footer">
        Système de Gestion de Présence — Foursquare La Porte des Cieux
    </div>

</body>
</html>
