<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Prendre un Rendez-vous</title>

        <link rel="icon" type="image/jpeg" href="{{ asset('images/logo2.jpeg') }}">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: { primary: '#185696' }
                    }
                }
            }
        </script>
        <style>
            .bg-image {
                background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('/images/bg.jpeg');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
            }
        </style>
    </head>
    <body class="bg-image min-h-screen flex items-center justify-center py-8">
        <div class="max-w-xl w-full px-4">
            <div class="bg-white shadow-lg rounded-lg p-8">

                <div class="flex flex-col items-center mb-6">
                    <img src="{{ asset('images/logo2.jpeg') }}" width="70" alt="Logo" class="rounded-full mb-3">
                    <h1 class="text-2xl font-bold text-gray-800">Prendre un Rendez-vous</h1>
                    <p class="text-sm text-gray-500 mt-1 text-center">Remplissez ce formulaire et nous vous contacterons pour confirmer votre rendez-vous.</p>
                </div>

                @if($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('rendez-vous.store') }}" class="space-y-4">
                    @csrf

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom <span class="text-red-500">*</span></label>
                            <input type="text" name="nom" value="{{ old('nom') }}" required
                                   placeholder="Ex: KOUADIO"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prénom <span class="text-red-500">*</span></label>
                            <input type="text" name="prenom" value="{{ old('prenom') }}" required
                                   placeholder="Ex: Marie"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact <span class="text-red-500">*</span></label>
                        <input type="tel" name="contact" id="contact" value="{{ old('contact') }}" required
                               placeholder="07 78 54 13 55" inputmode="numeric" maxlength="14"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lieu d'habitation</label>
                        <input type="text" name="lieu_habitation" value="{{ old('lieu_habitation') }}"
                               placeholder="Ex: Abidjan, Yopougon"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Motif <span class="text-red-500">*</span></label>
                        <select name="motif" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                            <option value="">-- Choisir un motif --</option>
                            @foreach($motifs as $motif)
                                <option value="{{ $motif }}" {{ old('motif') === $motif ? 'selected' : '' }}>{{ $motif }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pasteur souhaité <span class="text-red-500">*</span></label>
                        <select name="pasteur" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                            <option value="">-- Choisir un pasteur --</option>
                            @foreach($pasteurs as $pasteur)
                                <option value="{{ $pasteur }}" {{ old('pasteur') === $pasteur ? 'selected' : '' }}>{{ $pasteur }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date souhaitée <span class="text-red-500">*</span></label>
                            <input type="date" name="date_souhaitee" value="{{ old('date_souhaitee') }}" required
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Heure souhaitée</label>
                            <input type="time" name="heure_souhaitee" value="{{ old('heure_souhaitee') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Message complémentaire</label>
                        <textarea name="message" rows="3" maxlength="1000"
                                  placeholder="Décrivez brièvement votre situation ou votre besoin (optionnel)..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm resize-none">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit"
                            class="w-full bg-[#185696] hover:bg-blue-800 text-white font-semibold py-3 px-4 rounded-md transition-colors duration-200">
                        Envoyer ma demande de rendez-vous
                    </button>
                </form>

                <div class="mt-4 text-center">
                    <a href="{{ route('attendance.index') }}" class="text-sm text-gray-500 hover:underline">
                        Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>

        <script>
            const contactInput = document.getElementById('contact');
            if (contactInput) {
                contactInput.addEventListener('input', function() {
                    let v = this.value.replace(/[^0-9]/g, '').slice(0, 10);
                    let formatted = '';
                    for (let i = 0; i < v.length; i++) {
                        if (i > 0 && i % 2 === 0) formatted += ' ';
                        formatted += v[i];
                    }
                    this.value = formatted;
                });
            }
        </script>
    </body>
</html>
