<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste de Présence — {{ $moisLabel }}</title>
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
        .stats-row {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px;
            margin: 10px 0;
        }
        .stat-card {
            border: 1px solid #e0e0e0;
            background: #f9f9f9;
            border-radius: 6px;
            padding: 8px;
            text-align: center;
        }
        .stat-number {
            font-size: 16px;
            font-weight: bold;
        }
        .stat-label {
            font-size: 9px;
            color: #666;
            margin-top: 2px;
        }
        table.grid {
            width: 100%;
            border-collapse: collapse;
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
        .grid tfoot td {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .text-center { text-align: center !important; }
        .present { color: #27ae60; font-weight: bold; }
        .absent  { color: #c0392b; }
        .text-red    { color: #c0392b; font-weight: bold; }
        .text-green  { color: #27ae60; font-weight: bold; }
        .text-orange { color: #e67e22; font-weight: bold; }
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
        <div class="title">Liste de Présence Mensuelle des Membres</div>
        <div class="subtitle">{{ $moisLabel }}</div>
        <div class="subtitle">Généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</div>
    </div>

    @if($cultes->count() === 0)
        <p>Aucun culte passé trouvé pour {{ $moisLabel }}.</p>
    @else
        @php
            $presentsParJour = $cultesByDay->keys()->mapWithKeys(
                fn($dateKey) => [$dateKey => $presences->filter(fn($d) => $d['jours'][$dateKey])->count()]
            );
            $moyenne = $totalJours > 0 ? round($presentsParJour->sum() / $totalJours) : 0;
        @endphp

        <table class="stats-row">
            <tr>
                <td class="stat-card">
                    <div class="stat-number">{{ $totalJours }}</div>
                    <div class="stat-label">Jours de culte ({{ $cultes->count() }} culte(s))</div>
                </td>
                <td class="stat-card">
                    <div class="stat-number">{{ $presences->count() }}</div>
                    <div class="stat-label">Membres listés</div>
                </td>
                <td class="stat-card">
                    <div class="stat-number text-green">{{ $presences->filter(fn($d) => $d['nb_presences'] === $totalJours)->count() }}</div>
                    <div class="stat-label">Présents à tous les jours</div>
                </td>
                <td class="stat-card">
                    <div class="stat-number">{{ $moyenne }}</div>
                    <div class="stat-label">Présents en moyenne / jour</div>
                </td>
            </tr>
        </table>

        <table class="grid">
            <thead>
                <tr>
                    <th class="text-center">N°</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Catégorie</th>
                    @foreach($cultesByDay as $dateKey => $cultesOfDay)
                        <th class="text-center">{{ \Carbon\Carbon::parse($dateKey)->format('d/m') }}</th>
                    @endforeach
                    <th class="text-center">Total</th>
                    <th class="text-center">Taux</th>
                </tr>
            </thead>
            <tbody>
                @foreach($presences as $i => $data)
                    @php
                        $taux = $data['taux_presence'];
                        $tauxClass = $taux >= 75 ? 'text-green' : ($taux >= 50 ? 'text-orange' : 'text-red');
                    @endphp
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>{{ $data['member']->last_name }}</td>
                        <td>{{ $data['member']->first_name }}@if($data['member']->type === 'invite') (Invité)@endif</td>
                        <td>{{ $data['member']->category->name ?? 'NC' }}</td>
                        @foreach($data['jours'] as $present)
                            <td class="text-center {{ $present ? 'present' : 'absent' }}">{{ $present ? 'P' : 'A' }}</td>
                        @endforeach
                        <td class="text-center">{{ $data['nb_presences'] }} / {{ $totalJours }}</td>
                        <td class="text-center {{ $tauxClass }}">{{ $taux }}%</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align:right;">Présents par jour</td>
                    @foreach($presentsParJour as $nb)
                        <td class="text-center">{{ $nb }}</td>
                    @endforeach
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>

        <p style="font-size:9px;color:#777;margin-top:6px;">P = Présent · A = Absent — un membre est compté présent un jour s'il a assisté à au moins un culte ce jour-là.</p>
    @endif

    <div class="footer">
        Système de Gestion de Présence — Foursquare La Porte des Cieux
    </div>

</body>
</html>
