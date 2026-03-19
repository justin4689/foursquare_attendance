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
        // Créer des catégories
        $categories = [
            ['name' => 'Enfant', 'description' => 'Moins de 13 ans'],
            ['name' => 'Jeune', 'description' => '13 à 25 ans'],
            ['name' => 'Homme', 'description' => 'Hommes adultes'],
            ['name' => 'Femme', 'description' => 'Femmes adultes'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']], $category);
        }

        // Créer des membres
        $members = [
            ['first_name' => 'Jean', 'last_name' => 'Dupont', 'type' => 'permanent', 'phone' => '0123456789', 'category_id' => 1],
            ['first_name' => 'Marie', 'last_name' => 'Martin', 'type' => 'permanent', 'phone' => '0234567890', 'category_id' => 2],
            ['first_name' => 'Pierre', 'last_name' => 'Durand', 'type' => 'permanent', 'phone' => '0345678901', 'category_id' => 3],
            ['first_name' => 'Sophie', 'last_name' => 'Lefebvre', 'type' => 'invite', 'phone' => '0456789012', 'category_id' => 3],
            ['first_name' => 'Paul', 'last_name' => 'Bernard', 'type' => 'permanent', 'phone' => '0567890123', 'category_id' => 4],
            ['first_name' => 'Isabelle', 'last_name' => 'Robert', 'type' => 'permanent', 'phone' => '0678901234', 'category_id' => 4],
            ['first_name' => 'Michel', 'last_name' => 'Richard', 'type' => 'invite', 'phone' => '0789012345', 'category_id' => 1],
            ['first_name' => 'Catherine', 'last_name' => 'Petit', 'type' => 'permanent', 'phone' => '0890123456', 'category_id' => 2],
            ['first_name' => 'David', 'last_name' => 'Dubois', 'type' => 'permanent', 'phone' => '0901234567', 'category_id' => 3],
            ['first_name' => 'Françoise', 'last_name' => 'Laurent', 'type' => 'invite', 'phone' => '0123456780', 'category_id' => 3],
            ['first_name' => 'Jacques', 'last_name' => 'Simon', 'type' => 'permanent', 'phone' => '0234567891', 'category_id' => 4],
            ['first_name' => 'Nathalie', 'last_name' => 'Michel', 'type' => 'permanent', 'phone' => '0345678902', 'category_id' => 2],
            ['first_name' => 'Philippe', 'last_name' => 'Garcia', 'type' => 'invite', 'phone' => '0456789013', 'category_id' => 1],
            ['first_name' => 'Monique', 'last_name' => 'Rousseau', 'type' => 'permanent', 'phone' => '0567890124', 'category_id' => 2],
            ['first_name' => 'Étienne', 'last_name' => 'Fournier', 'type' => 'invite', 'phone' => '0678901235', 'category_id' => 3],
        ];

        foreach ($members as $member) {
            Member::create($member);
        }

        // Créer des cultes avec horaires réalistes
        $cultes = [
            [
                'name' => 'Culte de Matin',
                'date' => Carbon::today()->subDays(14), // Il y a 2 semaines
                'heure' => '08:00',
                'fin' => '10:00'
            ],
            [
                'name' => 'Culte de Midi',
                'date' => Carbon::today()->subDays(14),
                'heure' => '11:00',
                'fin' => '13:00'
            ],
            [
                'name' => 'Culte Dominical',
                'date' => Carbon::today()->subDays(7), // La semaine dernière
                'heure' => '09:00',
                'fin' => '11:30'
            ],
            [
                'name' => 'Culte de Soir',
                'date' => Carbon::today()->subDays(3), // Il y a 3 jours
                'heure' => '16:00',
                'fin' => '18:00'
            ],
            [
                'name' => 'Culte Spécial',
                'date' => Carbon::today()->addDays(2), // Dans 2 jours
                'heure' => '19:00',
                'fin' => '21:00'
            ],
            [
                'name' => 'Culte de Matin',
                'date' => Carbon::today()->addDays(7), // La semaine prochaine
                'heure' => '08:00',
                'fin' => '10:00'
            ],
            [
                'name' => 'Culte Dominical',
                'date' => Carbon::today()->addDays(7),
                'heure' => '09:00',
                'fin' => '11:30'
            ],
            [
                'name' => 'Culte de Jeûne',
                'date' => Carbon::today()->addDays(10), // Dans 10 jours
                'heure' => '18:00',
                'fin' => '20:00'
            ],
        ];

        foreach ($cultes as $culte) {
            Culte::create($culte);
        }

        // Créer des présences pour les cultes passés
        $cultesPasses = Culte::where('date', '<=', Carbon::today())->get();
        $members = Member::all();
        
        foreach ($cultesPasses as $culte) {
            // Simuler 70-90% de présence
            $nombrePresences = intval($members->count() * (0.7 + rand(0, 20) / 100));
            $membresPresences = $members->random($nombrePresences);

            foreach ($members as $member) {
                $status = $membresPresences->contains($member);
                Attendance::create([
                    'culte_id' => $culte->id,
                    'member_id' => $member->id,
                    'status' => $status,
                ]);
            }
        }

        $this->command->info('Données de test créées : ' . $members->count() . ' membres, ' . count($cultes) . ' cultes avec présences.');
    }
}
