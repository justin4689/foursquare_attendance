<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Member;
use App\Models\Culte;
use App\Models\Attendance;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Données prédéfinies pour les noms et prénoms
        $firstNames = ['Jean', 'Marie', 'Pierre', 'Sophie', 'Paul', 'Isabelle', 'Michel', 'Catherine', 'David', 'Françoise', 
                      'Jacques', 'Nathalie', 'Philippe', 'Monique', 'Étienne', 'Anne', 'Bernard', 'Christine', 'Alain', 'Sylvie',
                      'Robert', 'Martine', 'Daniel', 'Brigitte', 'Claude', 'Nicole', 'Patrick', 'Valérie', 'Christian', 'Sandra',
                      'Stéphane', 'Laurence', 'Olivier', 'Céline', 'Thierry', 'Nathalie', 'Frédéric', 'Émilie', 'Marc', 'Aurélie',
                      'Laurent', 'Caroline', 'David', 'Julie', 'Sébastien', 'Marion', 'Nicolas', 'Camille', 'Alexandre', 'Léa',
                      'Thomas', 'Manon', 'Antoine', 'Charlotte', 'Julien', 'Laura', 'Guillaume', 'Emma', 'Lucas', 'Chloé',
                      'Gérard', 'Josiane', 'René', 'Simone', 'André', 'Raymonde', 'Roger', 'Yvonne', 'Marcel', 'Gisèle',
                      'Louis', 'Marguerite', 'Henri', 'Thérèse', 'Paul', 'Denise', 'Charles', 'Monique', 'Joseph', 'Catherine',
                      'Georges', 'Françoise', 'Raymond', 'Jacqueline', 'Émile', 'Suzanne', 'Albert', 'Renée', 'Joseph', 'Yvette',
                      'Victor', 'Marcelle', 'Léon', 'Paulette', 'Gaston', 'Lucienne', 'Fernand', 'Odette', 'Marcel', 'Henriette',
                      'Arthur', 'Alice', 'Louis', 'Rose', 'Gustave', 'Eugénie', 'Ernest', 'Berthe', 'Adrien', 'Cécile',
                      'Eugène', 'Marguerite', 'Hector', 'Adèle', 'Célestin', 'Léontine', 'Alphonse', 'Victoire', 'Théophile', 'Irène'];

        $lastNames = ['Martin', 'Bernard', 'Dubois', 'Thomas', 'Robert', 'Richard', 'Petit', 'Durand', 'Leroy', 'Moreau',
                     'Simon', 'Laurent', 'Lefebvre', 'Michel', 'Garcia', 'David', 'Bertrand', 'Roux', 'Vincent', 'Fournier',
                     'Morel', 'Fernandez', 'Girard', 'Bonnet', 'Dupont', 'Lambert', 'Fontaine', 'Rousseau', 'Muller', 'Lefevre',
                     'Faure', 'André', 'Gauthier', 'Arnaud', 'Perrin', 'Colin', 'Bernard', 'Carpentier', 'Sanchez', 'Gérard',
                     'Chevalier', 'Marty', 'Benoît', 'Robin', 'Olivier', 'Lévy', 'Barbier', 'Gaillard', 'Joly', 'Marie',
                     'Caron', 'Aubert', 'Nicolas', 'Pierre', 'Boulanger', 'Roy', 'Leclerc', 'Mallet', 'Humbert', 'Renaud',
                     'Vidal', 'Leclercq', 'Poirier', 'Mathieu', 'Adam', 'Berger', 'Lemoine', 'Philippe', 'Briand', 'Schneider',
                     'Guerin', 'Pichon', 'Marchand', 'Julien', 'Rivière', 'Carlier', 'Denis', 'Dubois', 'Renault', 'Coulon',
                     'Guillot', 'Marty', 'Blanc', 'Gautier', 'Marty', 'Marty', 'Marty', 'Marty', 'Marty', 'Marty'];

        $phonePrefixes = ['01', '02', '03', '04', '05', '06', '07'];

        $lieuxHabitation = [
            'Abidjan', 'Yopougon', 'Cocody', 'Bingerville', 'Plateau', 'Treichville', 'Marcory', 'Koumassi',
            'Adjame', 'Angré', 'Riviera', 'Port-Bouët', 'Anyama', 'Songon', 'Dabou'
        ];

        // Récupérer ou créer les catégories
        $categories = [
            ['name' => 'Enfant', 'description' => 'Moins de 13 ans'],
            ['name' => 'Jeune', 'description' => '13 à 25 ans'],
            ['name' => 'Homme', 'description' => 'Hommes adultes'],
            ['name' => 'Femme', 'description' => 'Femmes adultes'],
        ];

        $categoryIds = [];
        foreach ($categories as $category) {
            $cat = Category::firstOrCreate(['name' => $category['name']], $category);
            $categoryIds[] = $cat->id;
        }

        // Créer 150 membres permanents
        $this->command->info('Création de 150 membres permanents...');
        for ($i = 0; $i < 150; $i++) {
            Member::create([
                'first_name' => $firstNames[array_rand($firstNames)],
                'last_name' => $lastNames[array_rand($lastNames)],
                'type' => 'permanent',
                'phone' => (rand(0, 4) < 4) ? $phonePrefixes[array_rand($phonePrefixes)] . rand(10000000, 99999999) : null,
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'lieu_habitation' => (rand(0, 4) < 4) ? $lieuxHabitation[array_rand($lieuxHabitation)] : null,
                'anniversaire_jour_mois' => (rand(0, 4) < 3) ? str_pad((string) rand(1, 28), 2, '0', STR_PAD_LEFT) . '/' . str_pad((string) rand(1, 12), 2, '0', STR_PAD_LEFT) : null,
            ]);
        }

        // Créer 39 invités
        $this->command->info('Création de 39 invités...');
        for ($i = 0; $i < 39; $i++) {
            Member::create([
                'first_name' => $firstNames[array_rand($firstNames)],
                'last_name' => $lastNames[array_rand($lastNames)],
                'type' => 'invite',
                'phone' => (rand(0, 4) < 3) ? $phonePrefixes[array_rand($phonePrefixes)] . rand(10000000, 99999999) : null,
                'category_id' => $categoryIds[array_rand($categoryIds)],
            ]);
        }

        // Créer 40 cultes étalés sur 2 mois
        $this->command->info('Création de 40 cultes...');
        $culteNames = [
            'Culte Dominical', 'Culte de Matin', 'Culte de Midi', 'Culte de Soir',
            'Culte de Louange', 'Culte de Prière', 'Culte d\'Action de Grâce',
            'Culte de Noël', 'Culte de Pâques', 'Culte de Pentecôte',
            'Culte Spécial Jeunes', 'Culte des Enfants', 'Culte des Hommes',
            'Culte des Femmes', 'Culte Familial', 'Culte d\'Adoration',
            'Culte d\'Évangélisation', 'Culte de Guérison', 'Culte de Délivrance',
            'Culte de Jeûne et Prière', 'Culte de Réveil', 'Culte Missionnaire',
            'Culte d\'Intercession', 'Culte de Thanksgiving', 'Culte de Victoire',
            'Culte de Bénédiction', 'Culte de Paix', 'Culte de Joie',
            'Culte d\'Espérance', 'Culte de Foi', 'Culte d\'Amour',
            'Culte de Grâce', 'Culte de Miséricorde', 'Culte de Réconciliation',
            'Culte de Libération', 'Culte de Restauration', 'Culte de Transformation',
            'Culte de Consécration', 'Culte de Dévouement', 'Culte de Service',
            'Culte d\'Unité', 'Culte de Communion', 'Culte de Célébration'
        ];

        $heures = ['08:00', '09:00', '10:00', '11:00', '14:00', '16:00', '18:00', '19:00', '20:00'];

        for ($i = 0; $i < 40; $i++) {
            $date = Carbon::today()->subDays(rand(60, 1))->addDays(rand(0, 90));
            $heure = $heures[array_rand($heures)];
            $fin = Carbon::createFromTimeString($heure)->addHours(rand(1, 3))->format('H:i');

            Culte::create([
                'name' => $culteNames[array_rand($culteNames)],
                'date' => $date,
                'heure' => $heure,
                'fin' => $fin,
            ]);
        }

        // Créer des présences pour les cultes passés
        $cultesPasses = Culte::where('date', '<=', Carbon::today())->get();
        $allMembers = Member::all();
        
        $this->command->info('Création des présences pour ' . $cultesPasses->count() . ' cultes passés...');
        
        foreach ($cultesPasses as $culte) {
            // Pour les permanents: 75-95% de présence
            $permanents = $allMembers->where('type', 'permanent');
            $nombrePresencesPermanents = intval($permanents->count() * (0.75 + rand(0, 20) / 100));
            $permanentsPresences = $permanents->random($nombrePresencesPermanents);

            // Pour les invités: 40-70% de présence (moins réguliers)
            $invites = $allMembers->where('type', 'invite');
            $nombrePresencesInvites = intval($invites->count() * (0.40 + rand(0, 30) / 100));
            $invitesPresences = $invites->random(min($nombrePresencesInvites, $invites->count()));

            foreach ($allMembers as $member) {
                $status = false;
                if ($member->type === 'permanent') {
                    $status = $permanentsPresences->contains($member);
                } else {
                    $status = $invitesPresences->contains($member);
                }

                Attendance::create([
                    'culte_id' => $culte->id,
                    'member_id' => $member->id,
                    'status' => $status,
                ]);
            }
        }

        $this->command->info('Données de test créées avec succès :');
        $this->command->info('- ' . $allMembers->where('type', 'permanent')->count() . ' membres permanents');
        $this->command->info('- ' . $allMembers->where('type', 'invite')->count() . ' invités');
        $this->command->info('- ' . Culte::count() . ' cultes');
        $this->command->info('- ' . Attendance::count() . ' enregistrements de présence');
    }
}
