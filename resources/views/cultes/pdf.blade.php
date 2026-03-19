<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport de Présence - {{ $culte->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
            padding-bottom: 70px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .logo {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
        }
        .subtitle {
            font-size: 14px;
            color: #666;
        }
        .stats {
            margin: 20px 0;
        }
        .cards {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px;
        }
        .card {
            border: 1px solid #e5e7eb;
            background: #f8fafc;
            border-radius: 8px;
            padding: 12px 10px;
            text-align: center;
        }
        .stat-number {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
        .stat-label {
            font-size: 11px;
            color: #666;
        }
        .section {
            margin: 25px 0;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .present {
            background-color: #d4edda;
        }
        .absent {
            background-color: #f8d7da;
        }
        .category-stats {
            margin-top: 20px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ccc;
            padding-top: 10px;
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            margin: 0 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo2.jpeg') }}" alt="Logo" class="logo" onerror="this.style.display='none'">
        <div class="title">Rapport de Présence</div>
        <div class="subtitle">{{ $culte->name }}</div>
        <div class="subtitle">
            {{ $culte->date?->format('d/m/Y') ?? \Carbon\Carbon::parse($culte->date)->format('d/m/Y') }}
            @if($culte->heure) {{ $culte->heure->format('H:i') }} @endif
            @if($culte->fin) - {{ $culte->fin->format('H:i') }} @endif
        </div>
    </div>

    <div class="stats">
        <table class="cards">
            <tr>
                <td>
                    <div class="card">
                        <div class="stat-number">{{ $totalPresent }}</div>
                        <div class="stat-label">Présents</div>
                    </div>
                </td>
                <td>
                    <div class="card">
                        <div class="stat-number">{{ $totalAbsent }}</div>
                        <div class="stat-label">Absents</div>
                    </div>
                </td>
                <td>
                    <div class="card">
                        <div class="stat-number">{{ $totalMembers }}</div>
                        <div class="stat-label">Total Membres</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    @if($present->count() > 0)
    <div class="section">
        <div class="section-title">Liste des Présents ({{ $present->count() }})</div>
        <table>
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Catégorie</th>
                    <th>Contact</th>
                </tr>
            </thead>
           
            <tbody>
                @foreach($present as $index => $attendance)
                <tr class="present">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $attendance->member->last_name ?? 'NC' }}</td>
                    <td>{{ $attendance->member->first_name ?? 'NC' }}</td>
                    <td>{{ $attendance->member->category->name ?? 'NC' }}</td>
                    <td>{{ $attendance->member->phone ?? 'NC' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($absent->count() > 0)
    <div class="section">
        <div class="section-title">Liste des Absents ({{ $absent->count() }})</div>
        <table>
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Catégorie</th>
                    <th>Contact</th>
                </tr>
            </thead>
            <tbody>
                @foreach($absent as $index => $attendance)
                <tr class="absent">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $attendance->member->last_name ?? 'NC' }}</td>
                    <td>{{ $attendance->member->first_name ?? 'NC' }}</td>
                  
                    <td>{{ $attendance->member->category->name ?? 'NC' }}</td>
                      <td>{{ $attendance->member->phone ?? 'NC' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif


    <div class="footer">
        Rapport généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}<br>
        Système de Gestion de Présence - Foursquare La Porte des Cieux
    </div>
</body>
</html>
