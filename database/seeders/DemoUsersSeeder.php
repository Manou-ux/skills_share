<?php

namespace Database\Seeders;

use App\Models\ExchangeRequest;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserSkill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        $alice = User::updateOrCreate(
            ['email' => 'alice@demo.test'],
            [
                'name' => 'Alice Rakoto',
                'password' => $password,
                'city' => 'Antananarivo',
                'bio' => 'Développeuse web passionnée, j’aime transmettre Laravel et Python.',
            ]
        );

        $bruno = User::updateOrCreate(
            ['email' => 'bruno@demo.test'],
            [
                'name' => 'Bruno Raso',
                'password' => $password,
                'city' => 'Toamasina',
                'bio' => 'Musicien et polyglotte. Toujours partant pour un échange de savoirs.',
            ]
        );

        $clara = User::updateOrCreate(
            ['email' => 'clara@demo.test'],
            [
                'name' => 'Clara Andria',
                'password' => $password,
                'city' => 'Fianarantsoa',
                'bio' => 'Étudiante en design, j’apprends le code et j’enseigne l’anglais.',
            ]
        );

        $skill = fn (string $name) => Skill::where('name', $name)->firstOrFail()->id;

        $pairs = [
            [$alice, 'Laravel', 'offre', 'expert', 'Je propose de l’aide sur Laravel (CRUD, Eloquent, auth Breeze). Dispo le week-end.'],
            [$alice, 'Python', 'offre', 'intermediaire', 'Bases Python et scripts utiles pour automatiser des tâches.'],
            [$alice, 'Anglais', 'besoin', null, 'Je cherche à améliorer mon anglais conversationnel pour le travail.'],
            [$bruno, 'Guitare', 'offre', 'expert', 'Cours de guitare débutant/intermédiaire, accords et rythme.'],
            [$bruno, 'Anglais', 'offre', 'intermediaire', 'Conversation anglaise et vocabulaire du quotidien.'],
            [$bruno, 'Laravel', 'besoin', null, 'Je veux apprendre Laravel pour créer mon premier projet web.'],
            [$clara, 'Anglais', 'offre', 'expert', 'Cours d’anglais oral et écrit, niveau collège/lycée/université.'],
            [$clara, 'React', 'besoin', null, 'Je cherche un mentor React pour comprendre les composants et hooks.'],
            [$clara, 'Piano', 'besoin', null, 'Débutante au piano, je cherche quelqu’un de patient pour démarrer.'],
        ];

        foreach ($pairs as [$user, $skillName, $type, $niveau, $description]) {
            UserSkill::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'skill_id' => $skill($skillName),
                    'type' => $type,
                ],
                [
                    'niveau' => $niveau,
                    'description' => $description,
                ]
            );
        }

        ExchangeRequest::updateOrCreate(
            [
                'sender_id' => $bruno->id,
                'receiver_id' => $alice->id,
                'skill_id' => $skill('Laravel'),
            ],
            [
                'message' => 'Salut Alice, je voudrais apprendre Laravel. On peut échanger contre de l’anglais ?',
                'status' => 'en_attente',
            ]
        );

        ExchangeRequest::updateOrCreate(
            [
                'sender_id' => $clara->id,
                'receiver_id' => $bruno->id,
                'skill_id' => $skill('Guitare'),
            ],
            [
                'message' => 'Bonjour Bruno, intéressée par quelques bases de guitare !',
                'status' => 'en_attente',
            ]
        );
    }
}
