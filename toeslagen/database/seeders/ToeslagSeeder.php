<?php

namespace Database\Seeders;

use App\Models\Toeslag;
use Illuminate\Database\Seeder;

class ToeslagSeeder extends Seeder
{
    public function run(): void
    {
        $toeslagen = [
            [
                'slug' => 'huurtoeslag',
                'naam' => 'Huurtoeslag',
                'omschrijving' => 'Een bijdrage in de kosten van de huur als je een laag inkomen hebt.',
                'voorwaarden' => [
                    'Je bent 18 jaar of ouder',
                    'Je huurt een zelfstandige woning',
                    'Je inkomen en vermogen zijn niet te hoog',
                ],
                'max_inkomen' => 40000,
            ],
            [
                'slug' => 'zorgtoeslag',
                'naam' => 'Zorgtoeslag',
                'omschrijving' => 'Een bijdrage in de kosten van je zorgverzekering.',
                'voorwaarden' => [
                    'Je bent 18 jaar of ouder',
                    'Je hebt een Nederlandse zorgverzekering',
                    'Je inkomen en vermogen zijn niet te hoog',
                ],
                'max_inkomen' => 38000,
            ],
            [
                'slug' => 'kinderopvangtoeslag',
                'naam' => 'Kinderopvangtoeslag',
                'omschrijving' => 'Een bijdrage in de kosten van de kinderopvang voor je kind.',
                'voorwaarden' => [
                    'Je kind gaat naar een geregistreerde kinderopvang',
                    'Jij en je eventuele partner werken of studeren',
                    'Je betaalt zelf de kosten van de opvang',
                ],
                'max_inkomen' => 250000,
            ],
        ];

        foreach ($toeslagen as $toeslag) {
            Toeslag::updateOrCreate(['slug' => $toeslag['slug']], $toeslag);
        }
    }
}
