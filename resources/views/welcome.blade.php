<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Foursquare Eglise Internationnale') }}</title>
        
        <!-- Favicon -->
        <link rel="icon" type="image/jpeg" href="{{ asset('images/logo2.jpeg') }}">
        
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: '#185696',
                        },
                        backgroundImage: {
                            'bg-gradient': "linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('/images/bg.jpeg')",
                            'bg-gradient2': "linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('/images/bg2.jpeg')"
                        }
                    }
                }
            }
        </script>
        <style>
            .bg-image {
                background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('/images/bg.jpeg');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                animation: backgroundChange 10s infinite;
            }

            @keyframes backgroundChange {
                0%, 50% {
                    background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('/images/bg.jpeg');
                }
                51%, 100% {
                    background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('/images/bg2.jpeg');
                }
            }
        </style>
    </head>
    <body class="bg-image">
        <div class="min-h-screen flex items-center justify-center">
            <div class="max-w-4xl w-full p-6">
                <div class="bg-white shadow-lg rounded-lg p-8">
                   <div class="flex justify-center mb-6">
                    <img src="{{ asset('images/logo2.jpeg') }}" width="80" alt="Logo">
                   </div>

                    <!-- Tabs -->
                    <div class="flex justify-center mb-6">
                        <div class="inline-flex rounded-lg border border-gray-200">
                            <button type="button" id="attendanceTab" class="md:px-6 px-4 md:py-2 py-1 text-sm font-medium bg-primary text-white rounded-l-lg" onclick="showAttendance(event)">
                                {{ __('Déjà inscrit') }}
                            </button>
                            <button type="button" id="registerTab" class="md:px-6 px-4 md:py-2 py-1 text-sm font-medium text-gray-700 bg-white rounded-r-lg" onclick="showRegister(event)">
                                {{ __('Nouveau membre') }}
                            </button>
                        </div>
                    </div>

                    <!-- Verset Biblique -->
                    <div class="text-center mt-6 mb-6">
                        <p class="text-gray-600  font-bold italic text-sm">
                            "La maturité pour une pêche abondante en eau profonde." — Luc 5:4
                        </p>
                    </div>

                    <!-- Register Form -->
                    <div id="registerForm" class="space-y-6 hidden">
                        <form method="POST" action="{{ route('members.store.public') }}" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="first_name" :value="__('Prénom')" />
                                    <x-text-input id="first_name" name="first_name" type="text" class="block mt-1 w-full px-3 py-2 border border-gray-300 rounded-md" :value="old('first_name')" required autofocus placeholder="{{ __('Entrez votre prénom') }}" />
                                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="last_name" :value="__('Nom')" />
                                    <x-text-input id="last_name" name="last_name" type="text" class="block mt-1 w-full px-3 py-2 border border-gray-300 rounded-md" :value="old('last_name')" required placeholder="{{ __('Entrez votre nom') }}" />
                                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="phone" :value="__('Téléphone')" />
                                    <x-text-input id="phone" name="phone" type="text" class="block mt-1 w-full px-3 py-2 border border-gray-300 rounded-md" :value="old('phone')" placeholder="07 78 54 13 55" inputmode="numeric" maxlength="14" pattern="[0-9]{2} [0-9]{2} [0-9]{2} [0-9]{2} [0-9]{2}" title="Format: 07 78 54 13 55 (10 chiffres)" />
                                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                </div>
                                 <div>
                                <x-input-label for="category_id" :value="__('Catégorie')" />
                                <select id="category_id" name="category_id" class="block mt-1 w-full border border-gray-300 px-3 py-2 focus:border-[#185696] focus:ring-[#185696] rounded-md shadow-sm" required>
                                    <option value="">{{ __('Choisir une catégorie') }}</option>
                                    @foreach(\App\Models\Category::all() as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                            </div>

                            <div id="public-permanent-fields" class="hidden">
                                <x-input-label for="public_lieu_habitation" :value="__('Lieu d\'habitation')" />
                                <x-text-input id="public_lieu_habitation" name="lieu_habitation" type="text" class="block mt-1 w-full px-3 py-2 border border-gray-300 rounded-md" :value="old('lieu_habitation')" placeholder="{{ __('Ex: Abidjan, Yopougon') }}" />
                            </div>

                            <div id="public-permanent-fields-2" class="hidden">
                                <x-input-label for="public_anniversaire_jour_mois" :value="__('Anniversaire (jour/mois)')" />
                                <x-text-input id="public_anniversaire_jour_mois" name="anniversaire_jour_mois" type="text" class="block mt-1 w-full px-3 py-2 border border-gray-300 rounded-md" :value="old('anniversaire_jour_mois')" placeholder="{{ __('JJ/MM (ex: 05/11)') }}" inputmode="numeric" maxlength="5" pattern="\d{2}/\d{2}" title="JJ/MM" />
                            </div>

                            <div class="mt-4">
                                <x-input-label :value="__('Type de membre')" />
                                <div class="mt-2 space-y-2">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="type" value="permanent" checked class="form-radio text-blue-600" onchange="togglePublicPermanentFields()">
                                        <span class="ml-2 text-sm">Permanent</span>
                                    </label>
                                    <label class="inline-flex items-center ml-4">
                                        <input type="radio" name="type" value="invite" class="form-radio text-blue-600" onchange="togglePublicPermanentFields()">
                                        <span class="ml-2 text-sm">Invité</span>
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>
                            </div>
                          
                           
                            <div class="flex justify-center">
                                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-md hover:bg-primary/90 transition">
                                    {{ __('S\'inscrire') }}
                                </button>
                            </div>
                        </form>
                    </div>

                    

                    <!-- Attendance Form -->
                    <div id="attendanceForm" class="space-y-6">
                        <form method="GET" action="{{ route('attendance.search') }}" class="space-y-4">
                            @csrf
                            <div>
                                <x-input-label for="search" :value="__('Rechercher par nom')" />
                                <x-text-input id="search" name="search" required placeholder="{{ __('Tapez votre nom...') }}" type="text" class="block mt-1 w-full px-3 py-2 border border-gray-300 rounded-md" />
                            </div>
                            <div class="flex justify-center">
                                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-md hover:bg-primary/90 transition">
                                    {{ __('Rechercher') }}
                                </button>
                            </div>
                        </form>

                        @if(isset($members) && $members->isNotEmpty())
                            <div class="mt-6">
                                <h3 class="text-lg font-medium mb-4">{{ __('Résultats de recherche') }}</h3>
                                <div class="space-y-2">
                                    @foreach($members as $member)
                                        <div class="flex items-center justify-between p-3 border rounded-lg">
                                            <div>
                                                <span class="font-medium">{{ $member->first_name }} {{ $member->last_name }}</span>
                                                <span class="text-sm text-gray-500 ml-2">{{ $member->category->name ?? '' }}</span>
                                            </div>
                                            <form method="GET" action="{{ route('attendance.pointage') }}" class="inline">
                                                @csrf
                                                <input type="hidden" name="member_id" value="{{ $member->id }}">
                                                <button type="submit" class="bg-green-600 text-white px-4 py-1 rounded text-sm hover:bg-green-700 transition">
                                                    {{ __('Pointer') }}
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @elseif(isset($members))
                            <div class="mt-6 text-center text-gray-500">
                                {{ __('Aucun membre trouvé pour cette recherche.') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    
        <script>
            function maskPhone(el) {
                if (!el) return;
                let v = el.value.replace(/[^0-9]/g, '').slice(0, 10);
                
                // Reformater complètement à chaque frappe
                let formatted = '';
                for (let i = 0; i < v.length; i++) {
                    if (i > 0 && i % 2 === 0) {
                        formatted += ' ';
                    }
                    formatted += v[i];
                }
                
                el.value = formatted;
            }

            function togglePublicPermanentFields() {
                const type = document.querySelector('#registerForm input[name="type"]:checked')?.value;
                const block1 = document.getElementById('public-permanent-fields');
                const block2 = document.getElementById('public-permanent-fields-2');

                const isPermanent = type === 'permanent';
                if (block1) block1.classList.toggle('hidden', !isPermanent);
                if (block2) block2.classList.toggle('hidden', !isPermanent);
            }

            function maskJourMois(el) {
                if (!el) return;
                let v = (el.value || '').replace(/\D/g, '').slice(0, 4);
                if (v.length >= 3) {
                    v = v.slice(0, 2) + '/' + v.slice(2);
                }
                el.value = v;
            }

            togglePublicPermanentFields();
            const publicPhone = document.getElementById('phone');
            if (publicPhone) {
                publicPhone.addEventListener('input', () => maskPhone(publicPhone));
            }
            const publicAnniv = document.getElementById('public_anniversaire_jour_mois');
            if (publicAnniv) {
                publicAnniv.addEventListener('input', () => maskJourMois(publicAnniv));
                maskJourMois(publicAnniv);
            }
            function showRegister(e) {
                if (e) e.preventDefault();
                document.getElementById('attendanceForm').classList.add('hidden');
                document.getElementById('registerForm').classList.remove('hidden');
                document.getElementById('attendanceTab').classList.remove('bg-primary', 'text-white');
                document.getElementById('attendanceTab').classList.add('bg-white', 'text-gray-700');
                document.getElementById('registerTab').classList.add('bg-primary', 'text-white');
                document.getElementById('registerTab').classList.remove('bg-white', 'text-gray-700');
                togglePublicPermanentFields();
            }

            function showAttendance(e) {
                if (e) e.preventDefault();
                document.getElementById('registerForm').classList.add('hidden');
                document.getElementById('attendanceForm').classList.remove('hidden');
                document.getElementById('attendanceTab').classList.add('bg-primary', 'text-white');
                document.getElementById('attendanceTab').classList.remove('bg-white', 'text-gray-700');
                document.getElementById('registerTab').classList.remove('bg-primary', 'text-white');
                document.getElementById('registerTab').classList.add('bg-white', 'text-gray-700');
            }
        </script>
    </body>
</html>
