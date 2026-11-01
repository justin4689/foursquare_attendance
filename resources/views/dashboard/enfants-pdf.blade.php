<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des Enfants</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10px;
            margin: 15px;
            padding-bottom: 50px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .logo {
            width: 60px;
            height: auto;
            margin-bottom: 6px;
        }
        .title {
            font-size: 15px;
            font-weight: bold;
            margin: 6px 0 4px;
        }
        .subtitle {
            font-size: 11px;
            color: #555;
        }
        table.grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .grid th {
            background-color: #f0f0f0;
            font-weight: bold;
            padding: 4px 5px;
            border: 1px solid #ccc;
            text-align: left;
            font-size: 9px;
        }
        .grid td {
            padding: 3px 5px;
            border: 1px solid #ddd;
        }
        .grid tr:nth-child(even) td {
            background-color: #fafafa;
        }
        .text-center { text-align: center !important; }
        .footer {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            margin: 0 15px;
            border-top: 1px solid #ccc;
            padding-top: 5px;
            text-align: center;
            font-size: 9px;
            color: #999;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('images/logo2.jpeg') }}" alt="Logo" class="logo" onerror="this.style.display='none'">
        <div class="title">Liste des Enfants</div>
        <div class="subtitle">{{ $enfants->count() }} enfant(s) — {{ $enfants->filter(fn($d) => $d['member']->type === 'permanent')->count() }} permanent(s), {{ $enfants->filter(fn($d) => $d['member']->type === 'invite')->count() }} invité(s)</div>
        <div class="subtitle">Généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</div>
    </div>

    @if($enfants->count() === 0)
        <p>Aucun enfant enregistré pour le moment.</p>
    @else
        <table class="grid">
            <thead>
                <tr>
                    <th class="text-center">N°</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Type</th>
                    <th>Contact</th>
                    <th>Lieu d'habitation</th>
                    <th>Anniversaire</th>
                    <th>Inscrit le</th>
                    <th class="text-center">Présences</th>
                    <th>Dernière présence</th>
                </tr>
            </thead>
            <tbody>
                @foreach($enfants as $i => $data)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>{{ $data['member']->last_name }}</td>
                        <td>{{ $data['member']->first_name }}</td>
                        <td>{{ $data['member']->type === 'permanent' ? 'Permanent' : 'Invité' }}</td>
                        <td>{{ $data['member']->phone ?? '—' }}</td>
                        <td>{{ $data['member']->lieu_habitation ?? '—' }}</td>
                        <td>{{ $data['member']->anniversaire_jour_mois ?? '—' }}</td>
                        <td>{{ $data['date_inscription'] }}</td>
                        <td class="text-center">{{ $data['nb_presences'] }}</td>
                        <td>{{ $data['derniere_presence'] ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        Système de Gestion de Présence — Foursquare La Porte des Cieux
    </div>

</body>
</html>
