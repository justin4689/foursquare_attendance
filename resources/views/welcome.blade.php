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
                            <button id="attendanceTab" class="md:px-6 px-4 md:py-2 py-1 text-sm font-medium bg-primary text-white rounded-l-lg" onclick="showAttendance()">
                                {{ __('Déjà inscrit') }}
                            </button>
                            <button id="registerTab" class="md:px-6 px-4 md:py-2 py-1 text-sm font-medium text-gray-700 bg-white rounded-r-lg" onclick="showRegister()">
                                {{ __('Nouveau membre') }}
                            </button>
                        </div>
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
                                    <x-text-input id="phone" name="phone" type="text" class="block mt-1 w-full px-3 py-2 border border-gray-300 rounded-md" :value="old('phone')" placeholder="06 98 26 27 28" />
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

                            <div class="mt-4">
                                <x-input-label :value="__('Type de membre')" />
                                <div class="mt-2 space-y-2">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="type" value="permanent" checked class="form-radio text-blue-600">
                                        <span class="ml-2 text-sm">Permanent</span>
                                    </label>
                                    <label class="inline-flex items-center ml-4">
                                        <input type="radio" name="type" value="invite" class="form-radio text-blue-600">
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

        <!-- Statistiques -->
        <div class="mt-2 max-w-md mx-auto">
            <div class="bg-white/90 backdrop-blur-sm rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 text-center">{{ __('Notre Communauté') }}</h3>
                @php
                    $totalMembers = \App\Models\Member::count();
                    $permanentCount = \App\Models\Member::where('type', 'permanent')->count();
                    $inviteCount = \App\Models\Member::where('type', 'invite')->count();
                @endphp
                @if($totalMembers > 0)
                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ __('Permanents') }}</span>
                                <span class="font-medium">{{ $permanentCount }} ({{ round($permanentCount / $totalMembers * 100, 1) }}%)</span>
                            </div>
                            <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $permanentCount / $totalMembers * 100 }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ __('Invités') }}</span>
                                <span class="font-medium">{{ $inviteCount }} ({{ round($inviteCount / $totalMembers * 100, 1) }}%)</span>
                            </div>
                            <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-orange-600 h-2 rounded-full" style="width: {{ $inviteCount / $totalMembers * 100 }}%"></div>
                            </div>
                        </div>
                        <div class="text-center mt-4 pt-4 border-t">
                            <span class="text-2xl font-bold text-blue-600">{{ $totalMembers }}</span>
                            <div class="text-sm text-gray-500">{{ __('Membres totaux') }}</div>
                        </div>
                    </div>
                @else
                    <div class="text-center text-gray-500">
                        {{ __('Aucun membre inscrit pour le moment') }}
                    </div>
                @endif
            </div>
        </div>

        <script>
            function showRegister() {
                document.getElementById('registerForm').classList.remove('hidden');
                document.getElementById('attendanceForm').classList.add('hidden');
                document.getElementById('registerTab').classList.add('bg-primary', 'text-white');
                document.getElementById('registerTab').classList.remove('bg-white', 'text-gray-700');
                document.getElementById('attendanceTab').classList.remove('bg-primary', 'text-white');
                document.getElementById('attendanceTab').classList.add('bg-white', 'text-gray-700');
            }

            function showAttendance() {
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
