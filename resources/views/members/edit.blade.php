<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier un Membre') }}
        </h2>
    </x-slot>

    

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('members.update', $member) }}" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="first_name" :value="__('Prénom')" />
                            <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $member->first_name)" required autofocus />
                            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="last_name" :value="__('Nom')" />
                            <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $member->last_name)" required />
                            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="phone" :value="__('Téléphone')" />
                            <x-text-input id="phone" name="phone" type="tel" class="block mt-1 w-full" :value="old('phone', $member->phone)" inputmode="numeric" maxlength="14" pattern="[0-9]{2} [0-9]{2} [0-9]{2} [0-9]{2} [0-9]{2}" title="Format: 07 78 54 13 55 (10 chiffres)" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="type" :value="__('Type de membre')" />
                            <div class="mt-2 space-y-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="type" value="permanent" {{ $member->type === 'permanent' ? 'checked' : '' }} class="form-radio text-blue-600">
                                    <span class="ml-2">Permanent</span>
                                </label>
                                <label class="inline-flex items-center ml-4">
                                    <input type="radio" name="type" value="invite" {{ $member->type === 'invite' ? 'checked' : '' }} class="form-radio text-blue-600">
                                    <span class="ml-2">Invité</span>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <div id="permanent-fields" class="space-y-4">
                            <div>
                                <x-input-label for="lieu_habitation" :value="__('Lieu d\'habitation')" />
                                <x-text-input id="lieu_habitation" name="lieu_habitation" type="text" class="mt-1 block w-full" :value="old('lieu_habitation', $member->lieu_habitation)" placeholder="{{ __('Ex: Abidjan, Yopougon') }}" />
                                <x-input-error :messages="$errors->get('lieu_habitation')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="anniversaire_jour_mois" :value="__('Anniversaire (jour/mois)')" />
                                <x-text-input id="anniversaire_jour_mois" name="anniversaire_jour_mois" type="text" class="mt-1 block w-full" :value="old('anniversaire_jour_mois', $member->anniversaire_jour_mois)" placeholder="{{ __('JJ/MM (ex: 05/11)') }}" inputmode="numeric" maxlength="5" pattern="\d{2}/\d{2}" title="JJ/MM" />
                                <x-input-error :messages="$errors->get('anniversaire_jour_mois')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="category_id" :value="__('Catégorie')" />
                            <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 focus:border-[#185696] focus:ring-[#185696] rounded-md shadow-sm">
                                <option value="">{{ __('Choisir une catégorie') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $member->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <div class="flex gap-2">
                            <x-primary-button>
                                {{ __('Mettre à jour') }}
                            </x-primary-button>

                            <a href="{{ route('members.index') }}">
                                <x-secondary-button type="button">
                                    {{ __('Annuler') }}
                                </x-secondary-button>
                            </a>
                        </div>
                    </form>
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

        function maskJourMois(el) {
            if (!el) return;
            let v = (el.value || '').replace(/\D/g, '').slice(0, 4);
            if (v.length >= 3) {
                v = v.slice(0, 2) + '/' + v.slice(2);
            }
            el.value = v;
        }

        function togglePermanentFields() {
            const type = document.querySelector('input[name="type"]:checked')?.value;
            const block = document.getElementById('permanent-fields');
            if (!block) return;
            block.classList.toggle('hidden', type !== 'permanent');
        }

        document.querySelectorAll('input[name="type"]').forEach(r => r.addEventListener('change', togglePermanentFields));
        const phone = document.getElementById('phone');
        if (phone) {
            phone.addEventListener('input', () => maskPhone(phone));
        }
        const anniv = document.getElementById('anniversaire_jour_mois');
        if (anniv) {
            anniv.addEventListener('input', () => maskJourMois(anniv));
            maskJourMois(anniv);
        }
        togglePermanentFields();
    </script>
</x-app-layout>
