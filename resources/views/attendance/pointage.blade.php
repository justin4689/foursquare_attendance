<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ __('Pointage de présence') }}</title>
        
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
            <div class="max-w-2xl w-full p-6">
                <div class="bg-white shadow-lg rounded-lg p-8">
                    <div class="flex justify-center mb-6">
                        <img src="{{ asset('images/logo2.jpeg') }}" width="80" alt="Logo">
                    </div>

                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <p class="text-lg font-medium text-gray-700">
                            {{ __('Membre :') }} {{ $member->first_name }} {{ $member->last_name }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ __('Catégorie :') }} {{ $member->category->name ?? '—' }}
                        </p>
                    </div>

                    @if(session('pointage_success'))
                        <div class="mb-6 bg-white shadow-lg rounded-lg p-6 border border-green-100">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-lg font-semibold text-green-700">{{ __('Merci d\'être au culte !') }}</p>
                                    <p class="text-gray-700 mt-1">{{ __('Que Dieu vous bénisse et bon culte.') }}</p>
                                    @if(session('pointage_culte_name'))
                                        <p class="text-sm text-gray-600 mt-3">
                                            <span class="font-medium">{{ __('Culte :') }}</span>
                                            {{ session('pointage_culte_name') }}
                                            @if(session('pointage_culte_date'))
                                                - {{ session('pointage_culte_date') }}
                                            @endif
                                            @if(session('pointage_culte_heure'))
                                                - {{ session('pointage_culte_heure') }}
                                                @if(session('pointage_culte_fin'))
                                                    {{ __('à') }} {{ session('pointage_culte_fin') }}
                                                @endif
                                            @endif
                                        </p>
                                    @endif
                                </div>

                                <div class="shrink-0">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        {{ __('Pointage validé') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif

                     <x-slot name="header">
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                {{ __('Pointage pour ') . $member->first_name . ' ' . $member->last_name }}
                            </h2>
                        </x-slot>

                        

                        <div class="py-6">
                            <div class="">
                                <!-- Recommandation automatique -->
                                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                                    <div class=" text-gray-900">
                                     
                                        @if($culteActuel)
    <!-- Culte disponible pour pointage -->
    <div class=" bg-[#C01C27] p-4 rounded">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-semibold text-white">{{ $culteActuel->name }}</h4>
                <p class="text-white text-sm">
                    {{ $culteActuel->date->format('d/m/Y') }} - 
                    {{ $culteActuel->heure?->format('H:i') }} 
                    @if($culteActuel->fin) à {{ $culteActuel->fin->format('H:i') }} @endif
                </p>
                <p class="text-white font-medium mt-1"> {{ $message }}</p>
            </div>
           
        </div>
    </div>

@else
    <!-- Aucun culte disponible -->
    <div class="border-l-4 border-gray-500 bg-gray-50 p-4 rounded">
        <div class="text-center">
            <h4 class="font-semibold text-gray-800">{{ __('Aucun culte disponible') }}</h4>
            <p class="text-gray-600 text-sm mt-2">{{ __('Revenez plus tard pour pointer') }}</p>
        </div>
    </div>
@endif
                                    </div>
                                </div>

                                <!-- Formulaire de pointage -->
                                @if($culteActuel)
                                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                                        <div class="p-6 text-gray-900">
                                            <h3 class="text-lg font-semibold mb-4">{{ __('Pointage Rapide') }}</h3>

                                            @if($dejaPointe)
                                                <div class="mb-4 bg-gray-50 border border-gray-200 rounded-lg p-4">
                                                    <p class="text-gray-800 font-medium">{{ __('Vous avez déjà pointé pour ce culte.') }}</p>
                                                    <p class="text-sm text-gray-600 mt-1">{{ __('Merci et bon culte !') }}</p>
                                                </div>
                                            @endif
                                            
                                            <form method="POST" action="{{ route('attendance.store') }}" class="space-y-4">
                                                @csrf
                                                
                                                <!-- Culte sélectionné automatiquement -->
                                                <input type="hidden" name="culte_id" value="{{ $culteActuel->id }}">
                                                <input type="hidden" name="member_id" value="{{ $member->id }}">
                                                <input type="hidden" name="status" value="1">

                                                <div class="flex gap-2">
                                                    @if(!$dejaPointe)
                                                        <x-primary-button>
                                                            {{ __('Pointer') }}
                                                        </x-primary-button>
                                                    @endif

                                                    <a href="{{ route('attendance.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition-colors">
                                                        {{ __('Retour') }}
                                                    </a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif

                               
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </body>
</html>
