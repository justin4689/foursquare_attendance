<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport Mensuel des Absents — {{ $moisLabel }}</title>
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
        .cultes-list {
            font-size: 10px;
            color: #555;
            margin-bottom: 15px;
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
        .text-red    { color: #c0392b; font-weight: bold; }
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
        <div class="title">Rapport Mensuel des Membres Absents</div>
        <div class="subtitle">{{ $moisLabel }}</div>
        <div class="subtitle">Généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</div>
    </div>

    {{-- Statistiques --}}
    <table class="stats-row">
        <tr>
            <td class="stat-card">
                <div class="stat-number">{{ $totalJours }}</div>
                <div class="stat-label">Jours de culte</div>
                <div style="font-size:9px;color:#888;">{{ $cultes->count() }} culte(s) au total</div>
            </td>
            <td class="stat-card">
                <div class="stat-number">{{ $totalPermanents }}</div>
                <div class="stat-label">Membres permanents</div>
            </td>
            <td class="stat-card">
                <div class="stat-number text-red">{{ $membresAbsents->count() }}</div>
                <div class="stat-label">Membres avec absences</div>
            </td>
            <td class="stat-card">
                <div class="stat-number text-green">{{ $totalPermanents - $membresAbsents->count() }}</div>
                <div class="stat-label">Présents à tous les jours</div>
            </td>
        </tr>
    </table>

    {{-- Jours de culte du mois --}}
    <div class="section-title">Jours de culte du mois</div>
    <div class="cultes-list">
        @foreach($cultesByDay as $dateKey => $cultesOfDay)
            {{ \Carbon\Carbon::parse($dateKey)->format('d/m') }}
            @if($cultesOfDay->count() > 1)({{ $cultesOfDay->count() }} cultes)@else— {{ $cultesOfDay->first()->name }}@endif
            @if(!$loop->last) &nbsp;·&nbsp; @endif
        @endforeach
    </div>

    {{-- Tableau des absents --}}
    @if($membresAbsents->count() > 0)
    <div class="section-title">Liste des membres absents ({{ $membresAbsents->count() }})</div>
    <table>
        <thead>
            <tr>
                <th class="text-center">N°</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Catégorie</th>
                <th>Contact</th>
                <th class="text-center">Jours absents / Total</th>
                <th class="text-center">Taux présence</th>
                <th>Dates d'absence</th>
            </tr>
        </thead>
        <tbody>
            @foreach($membresAbsents as $i => $data)
                @php
                    $taux = $data['taux_presence'];
                    $tauxClass = $taux >= 75 ? 'text-green' : ($taux >= 50 ? 'text-orange' : 'text-red');
                @endphp
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $data['member']->last_name }}</td>
                    <td>{{ $data['member']->first_name }}</td>
                    <td>{{ $data['member']->category->name ?? 'NC' }}</td>
                    <td>{{ $data['member']->phone ?? '—' }}</td>
                    <td class="text-center text-red">{{ $data['nb_absences'] }} / {{ $totalJours }}</td>
                    <td class="text-center {{ $tauxClass }}">{{ $taux }}%</td>
                    <td>
                        @foreach($data['dates_absences'] as $absence)
                            {{ $absence['date'] }}@if(!$loop->last), @endif
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p style="color: #27ae60; font-weight: bold;">
            Tous les membres permanents étaient présents à chaque jour de culte de {{ $moisLabel }}.
        </p>
    @endif

    <div class="footer">
        Système de Gestion de Présence — Foursquare La Porte des Cieux
    </div>

</body>
</html>
