<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
            padding-bottom: 50px;
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
            text-align: center;
        }
        .stat-box {
            display: inline-block;
            margin: 0 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #f8f9fa;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .stat-label {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
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
        <div class="title">{{ $title }}</div>
        <div class="subtitle">Exporté le {{ $exported_at }}</div>
    </div>

    <div class="stats">
        <div class="stat-box">
            <div class="stat-number">{{ $members->count() }}</div>
            <div class="stat-label">{{ $type === 'invites' ? 'Total Invités' : 'Total Permanents' }}</div>
        </div>
    </div>

    <div class="section">
        <table>
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Catégorie</th>
                    @if($type !== 'invites')
                        <th>Lieu d'habitation</th>
                        <th>Anniversaire</th>
                    @endif
                    <th>Contact</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $index => $member)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $member->last_name ?? 'NC' }}</td>
                    <td>{{ $member->first_name ?? 'NC' }}</td>
                    <td>{{ $member->category->name ?? 'NC' }}</td>
                    @if($type !== 'invites')
                        <td>{{ $member->lieu_habitation ?? 'NC' }}</td>
                        <td>{{ $member->anniversaire_jour_mois ?? 'NC' }}</td>
                    @endif
                    <td>{{ $member->phone ?? 'NC' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        Rapport généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}<br>
        Système de Gestion de Présence - Foursquare La Porte des Cieux
    </div>
</body>
</html>
