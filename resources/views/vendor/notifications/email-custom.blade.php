<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #185696 0%, #2c7be5 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }
        .header img {
            max-width: 120px;
            height: auto;
            margin-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 24px;
            font-weight: 600;
            color: #185696;
            margin-bottom: 20px;
        }
        .message {
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .button {
            display: inline-block;
            background: #185696;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
        }
        .button:hover {
            background: #144272;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 5px 0;
            color: #6c757d;
            font-size: 14px;
        }
        .subcopy {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header avec logo -->
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" onerror="this.style.display='none'">
            <h1>{{ config('app.name') }}</h1>
        </div>

        <!-- Contenu principal -->
        <div class="content">
            {{-- Salutation --}}
            @if (! empty($greeting))
                <div class="greeting">{{ $greeting }}</div>
            @else
                @if ($level === 'error')
                    <div class="greeting">Oups !</div>
                @else
                    <div class="greeting">Bonjour !</div>
                @endif
            @endif

            {{-- Message d'introduction --}}
            <div class="message">
                @foreach ($introLines as $line)
                    <p>{{ $line }}</p>
                @endforeach
            </div>

            {{-- Bouton d'action --}}
            @isset($actionText)
                <div style="text-align: center;">
                    <a href="{{ $actionUrl }}" class="button">{{ $actionText }}</a>
                </div>
            @endisset

            {{-- Message de fin --}}
            @foreach ($outroLines as $line)
                <div class="message">
                    <p>{{ $line }}</p>
                </div>
            @endforeach

            {{-- Signature --}}
            @if (! empty($salutation))
                <div class="message">
                    <p>{{ $salutation }}</p>
                </div>
            @else
                <div class="message">
                    <p>Cordialement,<br>{{ config('app.name') }}</p>
                </div>
            @endif

            {{-- Lien de secours --}}
            @isset($actionText)
                <div class="subcopy">
                    <p>Si vous avez des difficultés à cliquer sur le bouton "{{ $actionText }", copiez et collez l'URL ci-dessous dans votre navigateur web :</p>
                    <p><a href="{{ $actionUrl }}" style="color: #185696; word-break: break-all;">{{ $displayableActionUrl }}</a></p>
                </div>
            @endisset
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.</p>
            <p>Cet email a été envoyé automatiquement. Merci de ne pas répondre.</p>
        </div>
    </div>
</body>
</html>
