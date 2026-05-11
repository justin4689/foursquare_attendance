<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Demande envoyée</title>
        <link rel="icon" type="image/jpeg" href="{{ asset('images/logo2.jpeg') }}">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            .bg-image {
                background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('/images/bg.jpeg');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
            }
        </style>
    </head>
    <body class="bg-image min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full px-4">
            <div class="bg-white shadow-lg rounded-lg p-8 text-center">
                <img src="{{ asset('images/logo2.jpeg') }}" width="70" alt="Logo" class="rounded-full mx-auto mb-4">

                <div class="flex justify-center mb-4">
                    <div class="bg-green-100 rounded-full p-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>

                <h1 class="text-2xl font-bold text-gray-800 mb-2">Demande envoyée !</h1>
                <p class="text-gray-600 mb-2">
                    Votre demande de rendez-vous a bien été reçue.
                </p>
                <p class="text-gray-500 text-sm mb-6">
                    Nous vous contacterons dans les plus brefs délais pour confirmer votre rendez-vous. Que Dieu vous bénisse !
                </p>

                <div class="space-y-2">
                    <a href="{{ route('rendez-vous.create') }}"
                       class="block w-full bg-[#185696] hover:bg-blue-800 text-white font-medium py-2 px-4 rounded-md transition-colors">
                        Faire une autre demande
                    </a>
                    <a href="{{ route('attendance.index') }}"
                       class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-md transition-colors">
                        Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
